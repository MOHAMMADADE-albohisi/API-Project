<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegisterSellerController;
use App\Http\Controllers\Controller;
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

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/RegisterSeller', [RegisterSellerController::class, 'store']);
});

Route::prefix('cms/admin')->middleware('auth:seller')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout']);
});
