<?php

use App\Http\Controllers\Api\PayPalPaymentController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});
Route::resource('category', CategoryController::class);

Route::get('payments/pay', [PayPalPaymentController::class, 'sendPayment']);
Route::get('payments/verify/{payment?}', [PayPalPaymentController::class, 'payment_verify'])->name('verify-payment');
