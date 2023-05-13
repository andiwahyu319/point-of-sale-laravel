<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransactionDetailController extends Controller
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
        $this->validate($request, [
            "transaction_id" => "required|int",
            "product_id" => "required|int",
            "qty" => "required|int"
        ]);
        Product::where("id", $request->product_id)->decrement("qty", $request->qty);
        TransactionDetail::create($request->all());
        return '{"status": "ok"}';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransactionDetail  $transactionDetail
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransactionDetail  $transactionDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransactionDetail  $transactionDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransactionDetail $transactionDetail)
    {
        $this->validate($request, [
            "product_id" => "required|int",
            "qty" => "required|int"
        ]);
        Product::where("id", $transactionDetail->product_id)->increment("qty", $transactionDetail->qty);
        $transactionDetail->update([
            "product_id" => $request->product_id,
            "qty" => $request->qty
        ]);
        Product::where("id", $request->product_id)->decrement("qty", $request->qty);
        return '{"status": "ok"}';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransactionDetail  $transactionDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionDetail $transactionDetail)
    {
        Product::where("id", $transactionDetail->product_id)->increment("qty", $transactionDetail->qty);
        $transactionDetail->delete();
        return '{"status": "ok"}';
    }
}
