<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenMiddleware;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RecentlyProductCrontroller;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\OrderManagementController;

Route::prefix('api')->group(function () {
    ////////////
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/check-auth', [LoginController::class, 'checkAuth']);
    Route::get('/user', [LoginController::class, 'user']);
    Route::get('/products/home', [HomeController::class, 'getProductHome']);
    Route::get('/product/view/{id}', [ProductController::class, 'view']);
    Route::post('/check-stock', [ProductController::class, 'checkStock']);
    Route::post('/add-cart', [CartController::class, 'addcart']);
    Route::get('/cart-quantity', [CartController::class, 'getCartQuantity']);
    Route::get('/cart', [CartController::class, 'cartView']);
    Route::delete('/cart/remove/{id}/{size}/{color}/{quantity}', [CartController::class, 'removeItem']);
    Route::put('/cart/update-quantity/{id}', [CartController::class, 'updateQuantity']);
    Route::get('/cart/subtotal-total', [CartController::class, 'SubtotalTotal']);
    Route::get('/user-data/{id}', [UserController::class, 'userData']);
    Route::get('/user-profile/{id}', [UserController::class, 'userProfile']);
    Route::get('/account/products', [UserController::class, 'userProducts']);
    Route::get('/products/related', [ProductController::class, 'relatedProduct']);
    Route::get('/slider', [SliderController::class, 'slider']);
    Route::get('/section', [SectionController::class, 'getSection']);
    Route::get('/slider-sale', [SliderController::class, 'getSliderSale']);
    Route::get('/user/follow/{page}/{id}', [FollowController::class, 'userFollow']);
    Route::get('/user/follower/{page}/{id}', [FollowController::class, 'userFollower']);
    Route::post('/addToRecently', [RecentlyProductCrontroller::class, 'addToRecently']);
    Route::get('/recently-products', [RecentlyProductCrontroller::class, 'getRecentlyProducts']);
    Route::get('/product/recently', [SliderController::class, 'getRecentlyProducts']);
    Route::get('/product/discount', [SliderController::class, 'getDiscountProducts']);
    Route::get('/checkout/payment', [CheckoutController::class, 'paymentView']);
    Route::post('/handle/checkout', [CheckoutController::class, 'handlecheckout']);






    /////user
    Route::middleware(['user'])->group(function () {
        Route::get('/account/{id}', [ProfileController::class, 'account']);
        Route::get('/account/liked/{page}/{id}', [ProfileController::class, 'likedProducts']);
        Route::get('/account/user/edit/{id}', [ProfileController::class, 'accountEdit']);
        Route::post('/account/update', [ProfileController::class, 'accountUpdate']);
        Route::post('/upload/product/file', [ProductController::class, 'uploadFile']);
        Route::post('/upload/product', [ProductController::class, 'upload']);
        Route::post('/like', [LikeController::class, 'like']);
        Route::get('/checkout/view', [CheckoutController::class, 'checkoutView']);
        Route::post('/checkout/process', [CheckoutController::class, 'process']);
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::get('/user/post/{page}/{id}', [UserController::class, 'userPosts']);
        Route::get('/account/post/{id}', [UserController::class, 'accountPosts']);
        Route::get('/account/follow/{page}/{id}', [FollowController::class, 'follow']);
        Route::get('/account/follower/{page}/{id}', [FollowController::class, 'follower']);
        Route::post('/follow', [FollowController::class, 'handleFollow']);
        Route::get('/check-follow/{id}', [FollowController::class, 'checkFollow']);
        Route::post('/send-message', [MessageController::class, 'sendMessage']);
        Route::get('/chat', [MessageController::class, 'getChat']);
        Route::get('/chat/window/{id}', [MessageController::class, 'getUserInfor']);
        Route::get('/orders', [OrderManagementController::class, 'orders']);




       

    });
    /////admin
    Route::middleware(['admin'])->group(function () {
        Route::post('/slider/upload', [SliderController::class, 'HandleUpload']);
        Route::get('/slider/view', [SliderController::class, 'sliderEditView']);
        Route::delete('/slider/delete/{id}', [SliderController::class, 'removeSlider']);
        Route::get('/slider/edit/{id}', [SliderController::class, 'editSliderView']);
        Route::post('/slider/update', [SliderController::class, 'handleUpdate']);
        Route::post('/section/upload', [SectionController::class, 'HandleUpload']);
        Route::get('/section/view', [SectionController::class, 'view']);
        Route::delete('/section/delete/{id}', [SectionController::class, 'removeSection']);
        Route::get('/section/edit/{id}', [SectionController::class, 'editSectionView']);
        Route::post('/section/update', [SectionController::class, 'editSection']);
    });
});
Route::view('/{any}', 'app')->where('any', '.*');
