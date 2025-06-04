<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SenderController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ShipmentController;
// use App\Http\Controllers\CsController;
// use App\Http\Controllers\CategoryController;

// Sender Routes
Route::prefix('senders')->controller(SenderController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::put('{id}', 'update');
    Route::delete('{id}', 'destroy');
});

// Customer Routes
Route::prefix('customers')->controller(CustomerController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::put('{id}', 'update');
    Route::delete('{id}', 'destroy');
});

// Vendor Routes
Route::prefix('vendors')->controller(VendorController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::put('{id}', 'update');
    Route::delete('{id}', 'destroy');
});

// Courier Routes
Route::prefix('couriers')->controller(CourierController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::put('{id}', 'update');
    Route::delete('{id}', 'destroy');
});

// Package Routes
Route::prefix('packages')->controller(PackageController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::put('{id}', 'update');
    Route::delete('{id}', 'destroy');
});

// Shipment Routes
Route::prefix('shipments')->controller(ShipmentController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::put('{id}', 'update');
    Route::delete('{id}', 'destroy');
});

// Protected User Route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Optional / cadangan routes (nonaktif sementara)
// Route::get('cs', [CsController::class, 'listCS']);
// Route::get('senders/list', [SenderController::class, 'listSenders']);
// Route::get('category', [CategoryController::class, 'listCategory']);
