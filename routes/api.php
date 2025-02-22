<?php

use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::prefix('admin')->name('admin.')->middleware('auth:sanctum')->group(function () {
    Route::resource('/product-category', ProductCategoryController::class)->except(['show']);
    Route::post('/product-cmd', [ProductController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('product')->name('product.')->group(function () {
        Route::resource('', ProductController::class)->parameters(['' => 'product'])->except(['show']);
        Route::post('/generate-qrcode/{product}', [ProductController::class, 'generateQrcode'])->name('generate-qrcode');
    });
});
