<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (!Auth::check()) {
        return view('auth.login');
    } else {
        return redirect('home');
    }
});

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/api/transaction/{transaction}', [App\Http\Controllers\HomeController::class, 'apiTransaction']);
Route::get('/api/{api}', [App\Http\Controllers\HomeController::class, 'api']);
Route::get('/product_images/{filename}', [App\Http\Controllers\HomeController::class, 'productImage']);

Route::resource('product', App\Http\Controllers\ProductController::class);
Route::resource('transaction', App\Http\Controllers\TransactionController::class)->except('show');
Route::resource('transaction_detail', App\Http\Controllers\TransactionDetailController::class)->except(['index','show', 'create', 'edit']);
Route::resource('account', App\Http\Controllers\AccountController::class)->except(['show', 'create', 'edit']);