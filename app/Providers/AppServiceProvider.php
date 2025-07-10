<?php

namespace App\Providers;

use App\Repositories\AddressRepository;
use App\Repositories\AreaRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryTypeRepository;
use App\Repositories\CityRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CouponRepository;
use App\Repositories\DesignationRepository;
use App\Repositories\DistributorRepository;
use App\Repositories\MeetingRepository;
use App\Repositories\MeetingTypeRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderStatusRepository;
use App\Repositories\ProductRepository;
use App\Repositories\RouteRepository;
use App\Repositories\ShopRepository;
use App\Repositories\StateRepository;
use App\Repositories\TadaRepository;
use App\Repositories\TadaTypeRepository;
use App\Repositories\UserRepository;
use App\Repositories\VariantTypeRepository;
use App\Repositories\VariantValueRepository;
use App\Repositories\ZoneRepository;
use App\Services\AddressService;
use App\Services\AreaService;
use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\CategoryTypeService;
use App\Services\CityService;
use App\Services\CountryService;
use App\Services\CouponService;
use App\Services\DesignationService;
use App\Services\DistributorService;
use App\Services\LoginService;
use App\Services\MeetingService;
use App\Services\MeetingTypeService;
use App\Services\OrderService;
use App\Services\OrderStatusService;
use App\Services\ProductService;
use App\Services\RouteService;
use App\Services\ShopService;
use App\Services\StateService;
use App\Services\TadaService;
use App\Services\TadaTypeService;
use App\Services\UserService;
use App\Services\VariantTypeService;
use App\Services\VariantValueService;
use App\Services\ZoneService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('login.service', function (Application $app) {
            return new LoginService;
        });
        $this->app->singleton('route.service', function (Application $app) {
            return new RouteService($app->make(RouteRepository::class), $app->make(OrderRepository::class), $app->make(MeetingRepository::class));
        });
        $this->app->singleton('country.service', function (Application $app) {
            return new CountryService($app->make(CountryRepository::class));
        });
        $this->app->singleton('state.service', function (Application $app) {
            return new StateService($app->make(StateRepository::class));
        });
        $this->app->singleton('city.service', function (Application $app) {
            return new CityService($app->make(CityRepository::class));
        });
        $this->app->singleton('area.service', function (Application $app) {
            return new AreaService($app->make(AreaRepository::class));
        });
        $this->app->singleton('category.service', function (Application $app) {
            return new CategoryService($app->make(CategoryRepository::class));
        });
        $this->app->singleton('variant.type.service', function (Application $app) {
            return new VariantTypeService($app->make(VariantTypeRepository::class));
        });
        $this->app->singleton('variant.value.service', function (Application $app) {
            return new VariantValueService($app->make(VariantValueRepository::class));
        });
        $this->app->singleton('product.service', function (Application $app) {
            return new ProductService($app->make(ProductRepository::class));
        });
        $this->app->singleton('user.service', function (Application $app) {
            return new UserService($app->make(UserRepository::class));
        });
        $this->app->singleton('distributor.service', function (Application $app) {
            return new DistributorService($app->make(DistributorRepository::class), $app->make(AddressRepository::class));
        });
        $this->app->singleton('address.service', function (Application $app) {
            return new AddressService($app->make(AddressRepository::class));
        });
        $this->app->singleton('shop.service', function (Application $app) {
            return new ShopService($app->make(ShopRepository::class));
        });
        $this->app->singleton('meeting.type.service', function (Application $app) {
            return new MeetingTypeService($app->make(MeetingTypeRepository::class));
        });
        $this->app->singleton('meeting.service', function (Application $app) {
            return new MeetingService($app->make(MeetingRepository::class));
        });
        $this->app->singleton('coupon.service', function (Application $app) {
            return new CouponService($app->make(CouponRepository::class));
        });
        $this->app->singleton('cart.service', function (Application $app) {
            return new CartService;
        });
        $this->app->singleton('order.service', function (Application $app) {
            return new OrderService($app->make(OrderRepository::class));
        });
        $this->app->singleton('order.status.service', function (Application $app) {
            return new OrderStatusService($app->make(OrderStatusRepository::class));
        });
        $this->app->singleton('tada.type.service', function (Application $app) {
            return new TadaTypeService($app->make(TadaTypeRepository::class));
        });
        $this->app->singleton('tada.service', function (Application $app) {
            return new TadaService($app->make(TadaRepository::class));
        });
        $this->app->singleton('designation.service', function (Application $app) {
            return new DesignationService($app->make(DesignationRepository::class));
        });
        $this->app->singleton('zone.service', function (Application $app) {
            return new ZoneService($app->make(ZoneRepository::class));
        });
        $this->app->singleton('category.type.service', function ($app) {
            return new CategoryTypeService($app->make(CategoryTypeRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
