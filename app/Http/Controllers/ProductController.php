<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\ImgTinyOptimiser;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        //
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
        $product = new Product([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'img_path' => $request->filepath
        ]);
        $product->save();
        ImgTinyOptimiser::optimiseImg($request->filepath);
        $product->categories()->attach($request->categories);
        return $this->dataForAdmin();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        $categories = $product->categories()->get();
        return View('pages.product', compact(['product', 'categories']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->img_path = $request->filepath;
        $product->save();
        ImgTinyOptimiser::optimiseImg($request->filepath);
        $product->categories()->detach();
        $product->categories()->attach($request->categories);
        return $this->dataForAdmin();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return $this->dataForAdmin();
    }
}
