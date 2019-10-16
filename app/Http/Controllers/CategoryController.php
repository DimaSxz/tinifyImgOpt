<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\ImgTinyOptimiser;
use App\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private function dataForAdmin() {
        $categories = Category::all();
        $products = Product::all();
        return view('admin.container', compact(['categories', 'products']));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return View('pages.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category([
            'name' => $request->name,
            'description' => $request->description,
            'img_path' => $request->filepath
        ]);
        $category->save();
        return $this->dataForAdmin();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        $products = $category->products()->get();
        return View('pages.category', compact(['category', 'products']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $category = Category::find($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->img_path= $request->filepath;
        $category->save();
        return $this->dataForAdmin();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::destroy($id);
        return $this->dataForAdmin();
    }
}
