<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\orderController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
Route::get($uri, $callback);
Route::post($uri, $callback);
Route::put($uri, $callback);
Route::patch($uri, $callback);
Route::delete($uri, $callback);
Route::options($uri, $callback);
*/


Route::get('/', function () {
    return view('welcome');
});


Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class) ;
Route::resource('brand', BrandController::class);

Route::get('cart', [CartController::class, 'index'])->name('cart.index');

Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::post('/cart/increase', [CartController::class, 'increaseCart'])->name('cart.increase');
Route::post('/cart/decrease', [CartController::class, 'decreaseCart'])->name('cart.decrease');
Route::delete('/cart-delete', [CartController::class,'cartDelete'])->name('cart-delete');

Route::get('/checkout', [OrderController::class, 'create'])->name('checkout')->middleware('auth');
Route::post('/checkout', [OrderController::class, 'store'])->name('order.store')->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
