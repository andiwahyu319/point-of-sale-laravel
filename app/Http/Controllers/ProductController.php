<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('hasLogin.product.index');
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
        $this->validate($request, [
            "image" => "required|image|mimes:png,jpg,jpeg",
            "barcode" => "required|regex:/^[0-9]+$/|max:15|unique:products,barcode",
            "name" => "required",
            "qty" => "required|integer",
            "price" => "required|integer",
            "description" => "required"
        ]);
        $image = str_replace("/", "", str_replace(" ", "_", $request->name)) . "." . $request->image->extension();
        $request->image->storeAs("images", "product/" . $image);
        $request["img"] = $image;
        Product::create($request->all());
        return '{"status": "ok"}';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            "image" => "image|mimes:png,jpg,jpeg",
            "barcode" => "required|regex:/^[0-9]+$/|max:15",
            "name" => "required",
            "qty" => "required|integer",
            "price" => "required|integer",
            "description" => "required"
        ]);
        if ($request->hasFile("image")) {
            $image = str_replace("/", "", str_replace(" ", "_", $request->name)) . "." . $request->image->extension();
            $request->image->storeAs("images", "product/" . $image);
            $request["img"] = $image;
        }
        $product->update($request->all());
        return '{"status": "ok"}';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Storage::delete("images/product/" . $product->img);
        $product->delete();
        return '{"status": "ok"}';
    }
}
