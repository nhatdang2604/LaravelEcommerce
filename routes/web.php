<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::controller(App\Http\Controllers\Frontend\FrontendController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/collections', 'categories');
    Route::get('/collections/{category_slug}', 'products');
    Route::get('/collections/{category_slug}/{product_slug}', 'productView');
    Route::get('/new-arrivals', 'newArrival');
    Route::get('/featured-products', 'featuredProducts');
});

//For database query parallel
Route::get('/test', [App\Http\Controllers\Frontend\TestController::class, 'index']);
Route::get('/test2', [App\Http\Controllers\Frontend\TestController::class, 'index2']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Allow only for login user
Route::middleware(['auth'])->group(function() {
    Route::get('/wishlist', [App\Http\Controllers\Frontend\WishlistController::class, 'index']);
    Route::get('/cart', [App\Http\Controllers\Frontend\CartController::class, 'index']);
    Route::get('/checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'index']);
    Route::get('/thank-you', [App\Http\Controllers\Frontend\CheckoutController::class, 'thankYou']);
    Route::get('/orders', [App\Http\Controllers\Frontend\OrderController::class, 'index']);
    Route::get('/orders/{orderId}', [App\Http\Controllers\Frontend\OrderController::class, 'show']);

});

//ALlow only for admin
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function(){

    //Dashboard
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);

    //Site setting
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index']);
    Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'store']);

    //Category routes
    Route::controller(App\Http\Controllers\Admin\CategoryController::class)->group(function () {
        Route::get('/category', 'index');
        Route::get('/category/create', 'create');
        Route::get('/category/{category}/edit', 'edit');
        Route::put('/category/{category}', 'update');
        Route::post('/category', 'store');
    });

    //Products routes
    Route::controller(App\Http\Controllers\Admin\ProductController::class)->group(function () {
        Route::get('/products', 'index');
        Route::get('/products/create', 'create');
        Route::get('/products/{product}/edit', 'edit');
        Route::get('/product-image/{product_image_id}/delete', 'destroyImage');
        Route::get('/products/{product_id}/delete', 'destroy');
        Route::post('/products', 'store');
        Route::put('/products/{product}', 'update');

        Route::post('/product_color/{product_color_id}', 'updateProductColorQuantity');
        Route::delete('/product_color/{product_color_id}/delete', 'deleteProductColor');
    });

    //Color routes
    Route::controller(App\Http\Controllers\Admin\ColorController::class)->group(function () {
        Route::get('/colors', 'index');
        Route::get('/colors/create', 'create');
        Route::post('/colors/create', 'store');
        Route::get('/colors/{color}/edit', 'edit');
        Route::put('/colors/{color_id}', 'update');
        Route::get('/colors/{color_id}/delete', 'destroy');

    });

    Route::controller(App\Http\Controllers\Admin\SliderController::class)->group(function() {
        Route::get('/sliders', 'index');
        Route::get('/sliders/create', 'create');
        Route::post('/sliders/create', 'store');
        Route::get('/sliders/{slider}/edit', 'edit');
        Route::put('/sliders/{slider}', 'update');
        Route::get('/sliders/{slider}/delete', 'destroy');
    });

    Route::get('/brands', App\Http\Livewire\Admin\Brand\Index::class);

    Route::controller(App\Http\Controllers\Admin\OrderController::class)->group(function() {
        Route::get('/orders', 'index');
        Route::get('/orders/{orderId}', 'show');
        Route::put('/orders/{orderId}', 'updateOrderStatus');

        Route::get('/invoice/{orderId}', 'viewInvoice');
        Route::get('/invoice/{orderId}/generate', 'generateInvoice');

    });

    Route::controller(App\Http\Controllers\Admin\UserController::class)->group(function () {
        Route::get('/users', 'index');
        Route::get('/users/create', 'create');
        Route::post('/users', 'store');
        Route::get('/users/{userId}/edit', 'edit');
        Route::put('/users/{userId}', 'update');
        Route::get('/users/{userId}/delete', 'delete');

    });


});
