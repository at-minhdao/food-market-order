<?php

namespace App\Http\Controllers;

use App\Order;
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
}
