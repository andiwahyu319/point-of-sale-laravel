<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransactionController extends Controller
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
        return view('hasLogin.transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hasLogin.transaction.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cart = json_decode($request->cart);
        $request["cart"] = $cart;
        $this->validate($request, [
            "cashier_id" => "required|int",
            "buyer" => "required",
            "payment_via" => "required|in:cash,credit card",
            "card" => "required_if:payment_via,credit card"
        ]);
        $transaction = new Transaction;
        $transaction->cashier_id = $request->cashier_id;
        $transaction->buyer = $request->buyer;
        $transaction->payment_via = $request->payment_via;
        $transaction->card = ($request->payment_via == "cash") ? "-" : $request->card;
        $transaction->save();
        $transaction_id = $transaction->id;
        for ($i=0; $i < count($cart); $i++) { 
            $detail = new TransactionDetail;
            $detail->transaction_id = $transaction_id;
            $detail->product_id = $cart[$i]->product_id;
            $detail->qty = $cart[$i]->qty;
            $detail->save();
        }
        return redirect("transaction");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        return view('hasLogin.transaction.edit', compact("transaction"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->validate($request, [
            "buyer" => "required",
            "payment_via" => "required|in:cash,credit card",
            "card" => "required_if:payment_via,credit card"
        ]);
        $transaction->update([
            "buyer" => $request->buyer,
            "payment_via" => $request->payment_via,
            "card" => ($request->payment_via == "cash") ? "-" : $request->card
        ]);
        return redirect("transaction");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        TransactionDetail::where("transaction_id", $transaction->id)->delete();
        $transaction->delete();
        return redirect("transaction");
    }
}
