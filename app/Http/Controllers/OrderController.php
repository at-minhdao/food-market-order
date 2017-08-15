<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Variable common object Order
     *
     * @var Order $order
     */
    private $order;

    /**
     * OrderController constructor.
     *
     * @param Order $order It is param input constructors
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request Request from client
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = $this->order;
        // Filter date and key input
        if ($request->has('date')) {
            $orders = $orders->with('user')
                ->whereDate('updated_at', '=', $request->date)
                ->orWhereDate('created_at', '=', $request->date)
                ->orWhereDate('trans_at', '=', $request->date);
        } elseif ($request->has('keyword')) {
            $orders = $orders->whereHas('user', function ($query) use ($request) {
                    $query->where('full_name', 'like', '%'.$request->keyword.'%');
            })
                ->orWhere('custom_address', 'like', '%'.$request->keyword.'%')
                ->orWhere('payment', 'like', '%'.$request->keyword.'%');
        } else {
            $orders = $orders->with('user');
        }
        $orders = $orders->paginate(Order::ITEM_PER_PAGE);
        return view('orders.index', ['orders' => $orders]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id It is id of order need show detail
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orderitems = $this->order->with('orderItems.itemtable')->find($id);
        $orderitems = $orderitems->orderItems()->paginate(10);
        return view('orders.show', ['orderitems' => $orderitems]);
    }

    /**
     * Remove order item
     *
     * @param int $id It is id of order item need detele
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteItem($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        if ($orderItem->delete()) {
            flash(__('Delete Item Success'))->success()->important();
        } else {
            flash(__('Delete Item Errors'))->error()->important();
        }
        return back();
    }

    /**
     * Update order item
     *
     * @param \Illuminate\Http\Request $request Request from client
     * @param int                      $id      It is id of order item need update
     *
     * @return \Illuminate\Http\Response
     */
    public function updateItem(Request $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->quantity = $request->input('quantity');
        if ($orderItem->save()) {
            flash(__('Update Item '.$id.' Success'))->success()->important();
        } else {
            flash(__('Update Item Errors'))->error()->important();
        }
        return back();
    }
}
