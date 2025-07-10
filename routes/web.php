<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TadaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RetailerController;
use App\Http\Controllers\TadaTypeController;
use App\Http\Controllers\AllReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\MeetingTypeController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\VariantTypeController;
use App\Http\Controllers\CategoryTypeController;
use App\Http\Controllers\PrimaryOrderController;
use App\Http\Controllers\VariantValueController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/admin', function () {
    return redirect()->route('login');
});

Route::prefix('admin')->group(function () {
    require __DIR__ . '/auth.php';

    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->middleware(['auth', 'verified'])->name('dashboard');

    Route::group(['middleware' => ['auth']], function () {

        Route::get('/dashboard', DashboardController::class)->middleware(['verified'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('country', CountryController::class);
        Route::get('country_delete', [CountryController::class, 'multiple_delete'])->name('country.multiple_delete');
        Route::get('country_change_status', [CountryController::class, 'change_status'])->name('country.change_status');

        Route::resource('state', StateController::class);
        Route::get('state_delete', [StateController::class, 'multiple_delete'])->name('state.multiple_delete');
        Route::get('state_change_status', [StateController::class, 'change_status'])->name('state.change_status');
        Route::post('state_list', [StateController::class, 'state_list'])->name('state.state_list');

        Route::resource('city', CityController::class);
        Route::get('city_delete', [CityController::class, 'multiple_delete'])->name('city.multiple_delete');
        Route::get('city_change_status', [CityController::class, 'change_status'])->name('city.change_status');
        Route::post('city_list', [CityController::class, 'city_list'])->name('city.city_list');

        Route::resource('area', AreaController::class);
        Route::get('area_delete', [AreaController::class, 'multiple_delete'])->name('area.multiple_delete');
        Route::get('area_change_status', [AreaController::class, 'change_status'])->name('area.change_status');
        Route::post('area_list', [AreaController::class, 'area_list'])->name('area.area_list');

        Route::resource('category', CategoryController::class);
        Route::get('category_delete', [CategoryController::class, 'multiple_delete'])->name('category.multiple_delete');
        Route::get('category_change_status', [CategoryController::class, 'change_status'])->name('category.change_status');
        Route::post('category_delete_image', [CategoryController::class, 'delete_image'])->name('category.delete.image');
        Route::post('category_list', [CategoryController::class, 'category_list'])->name('category.category_list');

        Route::resource('varianttype', VariantTypeController::class);
        Route::get('varianttype_delete', [VariantTypeController::class, 'multiple_delete'])->name('varianttype.multiple_delete');
        Route::get('varianttype_change_status', [VariantTypeController::class, 'change_status'])->name('varianttype.change_status');

        Route::resource('variantvalue', VariantValueController::class);
        Route::get('variantvalue_delete', [VariantValueController::class, 'multiple_delete'])->name('variantvalue.multiple_delete');
        Route::get('variantvalue_change_status', [VariantValueController::class, 'change_status'])->name('variantvalue.change_status');

        Route::resource('product', ProductController::class);
        Route::get('product_delete', [ProductController::class, 'multiple_delete'])->name('product.multiple_delete');
        Route::get('product_change_status', [ProductController::class, 'change_status'])->name('product.change_status');
        Route::post('product_delete_image', [ProductController::class, 'delete_image'])->name('product.delete.image');
        Route::post('product_import', [ProductController::class, 'product_import'])->name('product.import');

        Route::resource('user', UserController::class);
        Route::get('user_delete', [UserController::class, 'multiple_delete'])->name('user.multiple_delete');
        Route::get('user_change_status', [UserController::class, 'change_status'])->name('user.change_status');

        Route::resource('distributor', DistributorController::class);
        Route::get('distributor_delete', [DistributorController::class, 'multiple_delete'])->name('distributor.multiple_delete');
        Route::get('distributor_change_status', [DistributorController::class, 'change_status'])->name('distributor.change_status');
        Route::post('distributor_delete_image', [DistributorController::class, 'delete_image'])->name('distributor.delete.image');
        Route::post('distributor_list', [DistributorController::class, 'distributor_list'])->name('distributor.distributor_list');

        Route::controller(AddressController::class)->prefix('address')->name('address.')->group(function () {
            Route::get('{userType}/{userId}', 'index')->name('index');
            Route::get('{userType}/{userId}/create', 'create')->name('create');
            Route::post('{userType}/{userId}', 'store')->name('store');
            Route::get('{userType}/{userId}/{address}/edit', 'edit')->name('edit');
            Route::put('{userType}/{userId}/{address}', 'update')->name('update');
        });
        Route::get('address_delete', [AddressController::class, 'multiple_delete'])->name('address.multiple_delete');

        Route::resource('shop', ShopController::class);
        Route::get('shop_delete', [ShopController::class, 'multiple_delete'])->name('shop.multiple_delete');
        Route::get('shop_change_status', [ShopController::class, 'change_status'])->name('shop.change_status');

        Route::resource('meetingtype', MeetingTypeController::class);
        Route::get('meetingtype_delete', [MeetingTypeController::class, 'multiple_delete'])->name('meetingtype.multiple_delete');
        Route::get('meetingtype_change_status', [MeetingTypeController::class, 'change_status'])->name('meetingtype.change_status');

        Route::get('meeting', [MeetingController::class, 'index'])->name('meeting.index');
        Route::get('meeting/{meeting}', [MeetingController::class, 'show'])->name('meeting.show');

        Route::get('route', [RouteController::class, 'index'])->name('route.index');
        Route::get('route/{route}', [RouteController::class, 'show'])->name('route.show');
        Route::match(['get', 'post'], 'attendance_report', [RouteController::class, 'attendance_report'])->name('route.attendance_report');
        Route::post('attendance_export', [RouteController::class, 'attendance_export'])->name('route.attendance_export');
        Route::match(['get', 'post'], 'employeetracking', [RouteController::class, 'employee_tracking'])->name('route.employee_tracking');
        Route::get('map', [RouteController::class, 'map'])->name('route.map');

        Route::resource('coupon', CouponController::class)->except(['show', 'destroy']);
        Route::get('coupon_delete', [CouponController::class, 'multiple_delete'])->name('coupon.multiple_delete');
        Route::get('coupon_change_status', [CouponController::class, 'change_status'])->name('coupon.change_status');
        Route::get('coupon/history', [CouponController::class, 'history'])->name('coupon.history');

        Route::resource('all-report', AllReportController::class);

        Route::get('/tada/report', [TadaController::class, 'localReport'])->name('tada.report');

        Route::post('/meetings/generate-report', [MeetingController::class, 'generateLocalPdfReport'])
            ->name('meetings.generate-report');
            
        Route::get('/get-distributors-for-report', [AllReportController::class, 'getDistributorsForReport'])->name('get.distributors.for.report');

        Route::post('/generate-report', [AllReportController::class, 'generateReport'])
            ->name('generate.report');

        Route::get('order', [OrderController::class, 'index'])->name('order.index');
        Route::get('order/{order}/edit', [OrderController::class, 'edit'])->name('order.edit');
        Route::get('order_delete', [OrderController::class, 'multiple_delete'])->name('order.multiple_delete');
        Route::post('orderhistory', [OrderController::class, 'order_history'])->name('orderhistory.store');
        Route::get('order/detail/{order_no}', [OrderController::class, 'my_order_details']);
        Route::get('no_order', [OrderController::class, 'no_order_list'])->name('order.no_order');

        Route::resource('orderstatus', OrderStatusController::class);
        Route::get('orderstatus_delete', [OrderStatusController::class, 'multiple_delete'])->name('orderstatus.multiple_delete');
        Route::get('orderstatus_change_status', [OrderStatusController::class, 'change_status'])->name('orderstatus.change_status');

        Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
        Route::post('setting/submit', [SettingController::class, 'setting_submit'])->name('setting.submit');

        Route::resource('tadatype', TadaTypeController::class);
        Route::get('tadatype_delete', [TadaTypeController::class, 'multiple_delete'])->name('tadatype.multiple_delete');
        Route::get('tadatype_change_status', [TadaTypeController::class, 'change_status'])->name('tadatype.change_status');

        Route::get('tada', [TadaController::class, 'index'])->name('tada.index');
        Route::get('tada_change_status', [TadaController::class, 'change_status'])->name('tada.change_status');

        Route::resource('designation', DesignationController::class);
        Route::get('designation_delete', [DesignationController::class, 'multiple_delete'])->name('designation.multiple_delete');
        Route::get('designation_change_status', [DesignationController::class, 'change_status'])->name('designation.change_status');

        Route::resource('zone', ZoneController::class);
        Route::get('zone_delete', [ZoneController::class, 'multiple_delete'])->name('zone.multiple_delete');
        Route::get('zone_change_status', [ZoneController::class, 'change_status'])->name('zone.change_status');

        Route::resource('categorytype', CategoryTypeController::class);
        Route::get('categorytype_delete', [CategoryTypeController::class, 'multiple_delete'])->name('categorytype.multiple_delete');
        Route::get('categorytype_change_status', [CategoryTypeController::class, 'change_status'])->name('categorytype.change_status');
    });
});

Route::get('report/{path}/{filename}', function ($path, $filename, Request $request) {
    $path = "{$path}/{$filename}";
    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File not found');
    }

    return response()->file(storage_path("app/public/{$path}"));
}); // ->middleware('auth');

Route::get('/meeting-count', [DashboardController::class, 'getMeetingCount']);

Route::get('/product-mrp-summary', [DashboardController::class, 'getProductMrpSummary']);

Route::get('/orders-count', [DashboardController::class, 'getOrdersCount']);

Route::get('/tada-amount-summary', [DashboardController::class, 'getTadaAmountSummary']);

Route::get('/null-shop-orders-count', [DashboardController::class, 'getNullShopOrdersCount']);

Route::get('/productive-vs-unproductive-orders', [DashboardController::class, 'getProductiveVsUnproductiveOrders']);
