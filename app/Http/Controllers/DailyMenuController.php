<?php

namespace App\Http\Controllers;

use Lang;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\DailyMenu;
use App\Http\Requests\DailyMenuUpdateItemRequest;
use App\Http\Requests\DailyMenuCreateRequest;
use App\Category;
use App\Food;
use Illuminate\Support\Facades\DB;

class DailyMenuController extends Controller
{
    /**
     * The Daily Menu implementation.
     *
     * @var DailyMenu
     */
    protected $dailyMenu;
    /**
     * The Food implementation.
     *
     * @var Food
     */
    protected $food;
    /**
     * The Category implementation.
     *
     * @var Category
     */
    protected $category;

    /**
     * Create a new controller instance.
     *
     * @param DailyMenu $dailyMenu instance of DailyMenu
     * @param Food      $food      instance of Food
     * @param Category  $category  instance of Category
     *
     * @return void
     */
    public function __construct(DailyMenu $dailyMenu, Food $food, Category $category)
    {
        $this->dailyMenu = $dailyMenu;
        $this->food = $food;
        $this->category = $category;
    }

    /**
     * Get list daily menus
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dailyMenus = $this->dailyMenu->search()->select('date')->distinct()->orderBy('date', 'DESC')->paginate(DailyMenu::ITEMS_PER_PAGE);
        return view('daily_menus.index', ['dailyMenus' => $dailyMenus]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request request value
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = $this->category->get();
        if ($request->has('date')) {
            return view('daily_menus.create', ['categories' => $categories, 'date' => $request['date']]);
        }
        return view('daily_menus.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DailyMenuCreateRequest $request request value
     *
     * @return \Illuminate\Http\Response
     */
    public function store(DailyMenuCreateRequest $request)
    {
        $date = $request->date;
        $foods = $request->food_id;
        $quantities = $request->quantity;
        $count = count($foods);
        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                $matchDailyMenu = $matchDailyMenu = array('date' => $date, 'food_id' => $foods[$i]);
                $this->dailyMenu->updateOrCreate($matchDailyMenu, ['quantity' => $quantities[$i]]);
            }
            DB::commit();
            $success = true;
            $message = __('Menu was successfully added!');
            $status = Response::HTTP_OK;
        } catch (\Exception $e) {
            $success = false;
            DB::rollback();
            $message = __('Has error during create new menu item!');
            $status = Response::HTTP_NOT_FOUND;
        } catch (ModelNotFoundException $ex) {
            $success = false;
            $message = __('Daily Menu Not Found');
            $status = Response::HTTP_BAD_REQUEST;
            DB::rollback();
        }
        if ($success) {
            return response()->json(['message' => $message, 'date' => $date], $status);
        } else {
            return response()->json(['message' => $message], $status);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $date The date to get menu to show
     *
     * @return \Illuminate\Http\Response
     */
    public function show($date)
    {
        $menuOnDate = $this->dailyMenu->with('food.category')->where('date', $date)->paginate($this->dailyMenu->ITEMS_PER_PAGE);

        return view('daily_menus.show', ['menuOnDate' => $menuOnDate, 'date' => $date]);
    }

    /**
     * Update item in Menu List on Date
     *
     * @param DailyMenuUpdateItemRequest $request The request message from Ajax request
     *
     * @return Response
     */
    public function update(DailyMenuUpdateItemRequest $request)
    {
        $quantity = $request['quantity'];
        $menuId = $request['menuId'];
        $dailyMenu = $this->dailyMenu->find($menuId);
        $error = __('Has error during update menu item');
        $success = __('Update menu item success');

        if ($dailyMenu->update(['quantity' => $quantity])) {
            $result = [$dailyMenu->updated_at, 'message' => $success];
            return response()->json($result, Response::HTTP_OK);
        } else {
            return response()->json(['error' => $error], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Delete item in Menu List on Date
     *
     * @param Request $request The request message from Ajax request
     * @param string  $date    The date value to delete
     *
     * @return Response
     */
    public function destroy(Request $request, $date)
    {
        $error = __('Has error during delete this');
        $success = __('Delete this menu success');

        if ($request->ajax()) {
            $menuId = $request['menuId'];
            if ($this->dailyMenu->where('id', $menuId)->delete()) {
                return response()->json(['message' => $success], Response::HTTP_OK);
            } else {
                return response()->json(['error' => $error], Response::HTTP_NOT_FOUND);
            }
        } else {
            if ($this->dailyMenu->where('date', $date)->delete()) {
                flash($success)->success()->important();
            } else {
                flash($error)->error()->important();
            }
        }
        return redirect()->route('daily-menus.index');
    }
}
