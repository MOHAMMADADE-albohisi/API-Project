<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BuyerController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductDetalisController;
use App\Http\Controllers\Api\RegisterSellerController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
  /*
  |--------------------------------------------------------------------------
  | Sellers Routes
  |--------------------------------------------------------------------------
  */
            Route::prefix('auth')->group(function () {
                Route::post('seller/login', [AuthController::class, 'login']);

                Route::post('seller/RegisterSeller', [RegisterSellerController::class, 'store']);
            });
                Route::prefix('cms/admin')->group(
                    function () {
                        Route::get('/store', [StoreController::class, 'index']);
                        Route::post('/store/create', [StoreController::class, 'store']);
                        Route::put('/store/{id}', [StoreController::class, 'update']);
                        Route::delete('store/{id}', [StoreController::class, 'destroy']);
                    }
                );


                Route::prefix('cms/admin')->middleware('auth:seller')->group(function () {

                    Route::get('/product', [ProductController::class, 'index']);
                    Route::post('/product/create', [ProductController::class, 'store']);
                    Route::put('/product/{id}', [ProductController::class, 'update']);
                    Route::delete('product/{id}', [ProductController::class, 'destroy']);


                    Route::get('/driver', [DriverController::class, 'index']);
                    Route::post('/driver/create', [DriverController::class, 'store']);
                    Route::put('/driver/{id}', [DriverController::class, 'update']);
                    Route::delete('driver/{id}', [DriverController::class, 'destroy']);
                    Route::get('/OrderProducts', [SellerController::class, 'OrderProducts']);

                    Route::get('/logout', [AuthController::class, 'logout']);
                });


  /*
  |--------------------------------------------------------------------------
  | Buyers Routes
  |--------------------------------------------------------------------------
  */

        Route::prefix('auth')->group(function () {
            Route::post('buyer/login', [BuyerController::class, 'login']);
            Route::post('buyer/RegisterBuyer', [BuyerController::class, 'store']);
            Route::get('/store', [StoreController::class, 'index']);
        });

            Route::prefix('cms/buyer')->group(
                function () {
                    Route::get('/store', [StoreController::class, 'index']);
                }
);

        Route::prefix('cms/buyer')->middleware('auth:buyer')->group(function () {

            Route::post('/product/{id}', [ProductDetalisController::class, 'ProductDetails']);

            Route::get('/driver', [DriverController::class, 'index']);

            Route::get('/order', [OrderController::class, 'index']);
            Route::post('/order/create', [OrderController::class, 'store']);
            Route::put('/order/{id}', [OrderController::class, 'update']);
            Route::delete('order/{id}', [OrderController::class, 'destroy']);

            Route::get('/logout', [BuyerController::class, 'logout']);
        });
