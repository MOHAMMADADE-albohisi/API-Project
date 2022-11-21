<?php

use App\Http\Controllers\Api\AuthSellerController;
use App\Http\Controllers\Api\AuthDriverController;
use App\Http\Controllers\Api\AuthBuyerController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\ComplainController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\HomeBuyerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderDriverController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RegisterSellerController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\SuggestionController;
use App\Http\Controllers\Controller;
use App\Models\Complain;
use App\Models\Product;
use App\Models\Suggestion;
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
Route::prefix('auth/')->group(function () {
    Route::post('seller/login', [AuthSellerController::class, 'login']);

    Route::post('seller/RegisterSeller', [AuthSellerController::class, 'store']);
});
Route::prefix('cms/admin/')->group(
    function () {
        Route::get('store', [StoreController::class, 'index']);
        Route::post('store/create', [StoreController::class, 'store']);
        Route::put('store/{id}', [StoreController::class, 'update']);
        Route::delete('store/{id}', [StoreController::class, 'destroy']);
    }
);

Route::prefix('cms/admin/')->middleware('auth:seller')->group(function () {
    Route::get('categorie', [CategorieController::class, 'index']);
    Route::get('product', [ProductController::class, 'index']);
    Route::post('product/create', [ProductController::class, 'store']);
    Route::post('product/{id}', [ProductController::class, 'update']);
    Route::delete('product/{id}', [ProductController::class, 'destroy']);
    Route::get('driver', [DriverController::class, 'index']);
    Route::post('driver/create', [DriverController::class, 'store']);
    Route::put('driver/{id}', [DriverController::class, 'update']);
    Route::delete('driver/{id}', [DriverController::class, 'destroy']);
    Route::get('orderProducts', [SellerController::class, 'OrderProducts']);
    Route::post('Products/{id}', [SellerController::class, 'Update']);
    Route::get('OrderDriver', [OrderDriverController::class, 'index']);
    Route::post('OrderDriver/create', [OrderDriverController::class, 'store']);
    Route::get('suggestion', [SellerController::class, 'Suggestion']);
    Route::get('complain', [SellerController::class, 'Complain']);
    Route::get('logout', [AuthSellerController::class, 'logout']);
});


/*
  |--------------------------------------------------------------------------
  | Buyers Routes
  |--------------------------------------------------------------------------
  */

Route::prefix('auth/')->group(function () {
    Route::post('buyer/login', [AuthBuyerController::class, 'login']);
    Route::post('buyer/RegisterBuyer', [AuthBuyerController::class, 'store']);
    Route::get('store', [StoreController::class, 'index']);
});

Route::prefix('cms/buyer/')->group(
    function () {
        Route::get('store', [StoreController::class, 'index']);
    }
);

Route::prefix('cms/buyer/')->middleware('auth:buyer')->group(function () {

    Route::get('', [HomeBuyerController::class, 'index']);
    Route::post('product/{id}', [ProductController::class, 'ProductDetails']);
    Route::get('driver', [DriverController::class, 'index']);
    Route::get('order', [OrderController::class, 'index']);
    Route::post('order/create', [OrderController::class, 'store']);
    Route::put('order/{id}', [OrderController::class, 'update']);
    Route::delete('order/{id}', [OrderController::class, 'destroy']);
    Route::get('suggestion', [SuggestionController::class, 'index']);
    Route::post('suggestion/create', [SuggestionController::class, 'store']);
    Route::get('complain', [ComplainController::class, 'index']);
    Route::post('complain/create', [ComplainController::class, 'store']);
    Route::get('logout', [AuthBuyerController::class, 'logout']);
});



/*
  |--------------------------------------------------------------------------
  | Drivers Routes
  |--------------------------------------------------------------------------
  */


Route::prefix('auth/')->group(function () {
    Route::post('driver/create', [DriverController::class, 'store']);
    Route::post('driver/login', [AuthDriverController::class, 'login']);
});

Route::prefix('cms/driver/')->group(
    function () {
        Route::get('store', [StoreController::class, 'index']);
    }
);

Route::prefix('cms/driver/')->middleware('auth:driver')->group(function () {
    Route::get('order', [DriverController::class, 'order']);
    Route::post('orderdetails/{id}', [DriverController::class, 'OrderDriver']);
    Route::get('logout', [AuthDriverController::class, 'logout']);
});
