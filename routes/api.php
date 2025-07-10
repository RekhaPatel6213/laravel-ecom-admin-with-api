<?php

use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\DistributorController;
use App\Http\Controllers\API\ListController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\MeetingController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RouteController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\API\TadaController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::controller(LoginController::class)->group(function () {
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('logout', [LoginController::class, 'logout']);
    Route::post('profile/update', [UserController::class, 'profile']);

    Route::get('user/detail', [UserController::class, 'detail']);
    Route::get('user/list', [UserController::class, 'list']);

    Route::post('route/start', [RouteController::class, 'start']);
    Route::post('route/end', [RouteController::class, 'end']);
    Route::post('route/daily_report', [RouteController::class, 'report']);

    Route::get('distributor', [DistributorController::class, 'list']);
    Route::post('distributor/store', [DistributorController::class, 'store']);
    Route::post('distributor/update', [DistributorController::class, 'update']);

    Route::get('shop/{distributor?}', [ShopController::class, 'list']);
    Route::post('shop/store', [ShopController::class, 'store']);

    Route::get('meeting/{distributor?}/{shop?}', [MeetingController::class, 'list']);
    Route::post('meeting/start', [MeetingController::class, 'start']);
    Route::post('meeting/end', [MeetingController::class, 'end']);
    Route::get('meeting_detail/{meeting}', [MeetingController::class, 'show']);
    Route::post('meeting/meeting_report', [MeetingController::class, 'report']);

    Route::get('country/list', [ListController::class, 'country_list']);
    Route::get('state/list', [ListController::class, 'state_list']);
    Route::get('city/list', [ListController::class, 'city_list']);
    Route::get('area/list', [AreaController::class, 'list']);
    Route::post('area/store', [AreaController::class, 'store']);
    Route::get('meeting_type/list', [ListController::class, 'meeting_type_list']);

    Route::get('main-category', [CategoryController::class, 'category_type']);
    Route::get('category', [CategoryController::class, 'category']);
    Route::get('product', ProductController::class);
    Route::post('cart', [CartController::class, 'cart']);
    Route::get('cart/remove', [CartController::class, 'remove_cart']);

    Route::post('coupon/check', [CouponController::class, 'check_coupon']);
    Route::post('coupon/remove', [CouponController::class, 'remove_coupon']);
    Route::post('order/place', [OrderController::class, 'order_place']);
    Route::get('order/list', [OrderController::class, 'list']);
    Route::post('order/date_rangewise_pdf_generate', [OrderController::class, 'report']);

    Route::post('order/no_order', [OrderController::class, 'no_order']);

    Route::get('tada_type/list', [TadaController::class, 'tada_type_list']);
    Route::get('tada', [TadaController::class, 'list']);
    Route::post('tada/store', [TadaController::class, 'store']);
    Route::post('tada/expense_report', [TadaController::class, 'report']);
});
