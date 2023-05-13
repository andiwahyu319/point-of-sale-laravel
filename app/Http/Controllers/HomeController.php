<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::all();
        $product_total = $products->count();
        $product_qty_min = $products->where("qty", "<", 5);
        $transactions = Transaction::whereMonth("created_at", date("m"));
        $transaction_total = $transactions->count();
        $detail = TransactionDetail::select(TransactionDetail::raw("SUM(transaction_details.qty * products.price) AS total"))
        ->join("products", "products.id", "=", "transaction_details.product_id")
        ->whereMonth("transaction_details.created_at", date("m"))->first();
        $total_price = $detail->total;
        $income = "Rp " . number_format($total_price, 0, ",", ".") . ",-";
        return view('home', compact("product_total", "product_qty_min", "transaction_total", "income"));
    }

    public function api($api)
    {
        switch ($api) {
            case "product":
                $products = Product::all();
                foreach ($products as $key => $product) {
                    $product["img"] = url("/product_images") . "/" . $product->img;
                }
                return $products;
                break;
            case "transaction":
                $transactions = Transaction::select(Transaction::raw("users.name as cashier"), "transactions.*")
                ->join("users", "users.id", "=", "transactions.cashier_id")->latest()->get();
                foreach ($transactions as $key => $transaction) {
                    $total_transaction = 0;
                    $transaction["cart"] = TransactionDetail::select("transaction_details.id", "products.name", "products.img", "products.price", "transaction_details.qty")
                    ->join("products", "products.id", "=", "transaction_details.product_id")
                    ->where("transaction_id", $transaction->id)->get();
                    foreach ($transaction->cart as $key => $product) {
                        $product["img"] = url("/product_images") . "/" . $product->img;
                        $total = $product->qty * $product->price;
                        $product["total"] = $total;
                        $total_transaction += $total; 
                    }
                    $transaction["total"] = $total_transaction;
                }
                return $transactions;
                break;
                
            default:
                return abort("404");
                break;
        }
    }

    public function apiTransaction(Transaction $transaction)
    {
        $total_transaction = 0;
        $cashier = User::where("id", $transaction->cashier_id)->first();
        $cart = TransactionDetail::select("transaction_details.id", "transaction_details.product_id", "products.name", "products.img", "products.price", "transaction_details.qty")
        ->join("products", "products.id", "=", "transaction_details.product_id")
        ->where("transaction_id", $transaction->id)->get();
        foreach ($cart as $key => $product) {
            $product["img"] = url("/product_images") . "/" . $product->img;
            $total = $product->qty * $product->price;
            $product["total"] = $total;
            $total_transaction += $total; 
        }
        $transaction["cashier"] = $cashier->name;
        $transaction["cart"] = $cart;
        $transaction["total"] = $total_transaction;
        return $transaction;
    }

    public function productImage($filename)
    {
        $path = storage_path('app/images/product/' . $filename);
        if (!File::exists($path)) {
            abort(404);
        }
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
