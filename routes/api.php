<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SenderController;
use App\Http\Controllers\API\VendorController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\CourierController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ShipmentController;

Route::group([], function () {
Route::apiResource('senders', SenderController::class);
Route::apiResource('cs', CsController::class);
Route::apiResource('vendors', VendorController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('shipments', ShipmentController::class);
Route::apiResource('couriers', CourierController::class);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group([], function () {
//     Route::get('cs', [CsController::class, 'listCS']);
// });
// Route::group([], function () {
//     Route::get('senders', [SenderController::class, 'listSenders']);
// });
// Route::group([], function () {
//     Route::get('category', [CategoryController::class, 'listCategory']);
// });