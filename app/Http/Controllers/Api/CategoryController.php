<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Category;

class CategoryController extends ApiController
{
    /**
     * The Category implementation.
     *
     * @var Category
     */
    protected $category;

    /**
     * Create a new controller instance.
     *
     * @param Category $category instance of Category
     *
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->category->select('id', 'name', 'description')
                                    ->paginate(Category::ITEMS_PER_PAGE);

        if ($categories) {
            return response()->json(collect(['success' => true])->merge($categories));
        }
        $error = __('Has error during access this page');

        return response()->json(['error' => $error]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id of category
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->category->select('id', 'name', 'description')->findOrFail($id);

        if ($category) {
            return response()->json(collect(['success' => true])->merge(['data' => $category]));
        }
        $error = __('Has error during access this page');

        return response()->json(['error' => $error]);
    }
}
