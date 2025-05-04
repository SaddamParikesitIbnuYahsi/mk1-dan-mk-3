<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import semua Controller yang akan digunakan
use App\Http\Controllers\SenderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ShipmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route bawaan Laravel untuk mendapatkan user yang sedang login (jika menggunakan auth:sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Mendaftarkan API Resource Routes untuk setiap Controller
// Ini secara otomatis membuat route untuk:
// GET       /senders           -> SenderController@index   (nama route: senders.index)
// POST      /senders           -> SenderController@store   (nama route: senders.store)
// GET       /senders/{sender}  -> SenderController@show    (nama route: senders.show)
// PUT/PATCH /senders/{sender}  -> SenderController@update  (nama route: senders.update)
// DELETE    /senders/{sender}  -> SenderController@destroy (nama route: senders.destroy)
// {sender} akan menjadi ID yang digunakan di findOrFail($id) atau Route Model Binding

Route::apiResource('senders', SenderController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('vendors', VendorController::class);
Route::apiResource('couriers', CourierController::class);
Route::apiResource('packages', PackageController::class);
Route::apiResource('shipments', ShipmentController::class);

// Anda bisa menambahkan route custom lainnya di sini jika diperlukan
// Contoh:
// Route::get('/packages/by-sender/{senderId}', [PackageController::class, 'getPackagesBySender']);

