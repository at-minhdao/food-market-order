<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\DailyMenu;

class DailyMenuController extends Controller
{
    /**
     * The Daily Menu implementation.
     *
     * @var DailyMenu
     */
    protected $dailyMenu;

    /**
     * Create a new controller instance.
     *
     * @param DailyMenu $dailyMenu instance of DailyMenu
     *
     * @return void
     */
    public function __construct(DailyMenu $dailyMenu)
    {
        $this->dailyMenu = $dailyMenu;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dailyMenus = $this->dailyMenu->search()->select('date')->distinct()->orderBy('date', 'DESC')->paginate(DailyMenu::ITEMS_PER_PAGE);
        return response()->json(collect(['success' => true])->merge($dailyMenus));
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

        if ($menu = $this->dailyMenu->select('date', 'food_id', 'quantity')
                                    ->with(['food' => function ($food) {
                                        $food->select('id', 'name', 'price', 'description', 'category_id', 'image');
                                    }, 'food.category' => function ($category) {
                                        $category->select('id', 'name', 'description');
                                    }])
                                    ->where('date', $date)
                                    ->paginate($this->dailyMenu->ITEMS_PER_PAGE)
        ) {
            return response()->json(collect(['success' => true])->merge($menu));
        }
        $error = __('Has error during access this page');
        return response()->json(['error' => $error]);
    }
}
