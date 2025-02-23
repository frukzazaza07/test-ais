<?php

use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    if (Auth::check()) {
        return redirect()->route('admin.product.index');
    }
    return redirect()->route('admin.login');
});

Route::prefix('admin')->name('admin.')->group(function () {

    require __DIR__ . '/auth.php';

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::middleware('verified')->group(function () {
            Route::resource('/product-category', ProductCategoryController::class)->except(['show']);

            Route::prefix('product')->name('product.')->group(function () {
                Route::resource('', ProductController::class)->parameters(['' => 'product'])->except(['show']);
                Route::post('/generate-qrcode/{product}', [ProductController::class, 'generateQrcode'])->name('generate-qrcode');
                Route::post('/import', [ProductController::class, 'import'])->name('import');
                Route::get('/export', [ProductController::class, 'export'])->name('export');
            });
        });
    });
});

Route::get('/{any}', function () {
    $beforeRoute = url()->previous() == url()->current() ? '/' : url()->previous();
    return Inertia::render('404', ['beforeRoute' => $beforeRoute]);
})->where('any', '.*');
