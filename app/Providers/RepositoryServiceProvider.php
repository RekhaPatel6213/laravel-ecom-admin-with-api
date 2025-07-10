<?php

namespace App\Providers;

use App\Contracts\BaseInterface;
use App\Contracts\ModalInterface;
use App\Repositories\AddressRepository;
use App\Repositories\AreaRepository;
use App\Repositories\BaseRepository;
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
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(BaseInterface::class, BaseRepository::class);
        $this->app->bind(ModalInterface::class, CountryRepository::class);
        $this->app->bind(ModalInterface::class, StateRepository::class);
        $this->app->bind(ModalInterface::class, CityRepository::class);
        $this->app->bind(ModalInterface::class, AreaRepository::class);
        $this->app->bind(ModalInterface::class, CategoryRepository::class);
        $this->app->bind(ModalInterface::class, VariantTypeRepository::class);
        $this->app->bind(ModalInterface::class, VariantValueRepository::class);
        $this->app->bind(ModalInterface::class, ProductRepository::class);
        $this->app->bind(ModalInterface::class, UserRepository::class);
        $this->app->bind(ModalInterface::class, DistributorRepository::class);
        $this->app->bind(ModalInterface::class, ShopRepository::class);
        $this->app->bind(ModalInterface::class, MeetingTypeRepository::class);
        $this->app->bind(ModalInterface::class, MeetingRepository::class);
        $this->app->bind(ModalInterface::class, CouponRepository::class);
        $this->app->bind(ModalInterface::class, OrderRepository::class);
        $this->app->bind(ModalInterface::class, RouteRepository::class);
        $this->app->bind(ModalInterface::class, AddressRepository::class);
        $this->app->bind(ModalInterface::class, OrderStatusRepository::class);
        $this->app->bind(ModalInterface::class, TadaTypeRepository::class);
        $this->app->bind(ModalInterface::class, TadaRepository::class);
        $this->app->bind(ModalInterface::class, DesignationRepository::class);
        $this->app->bind(ModalInterface::class, ZoneRepository::class);
        $this->app->bind(ModalInterface::class, CategoryTypeRepository::class);
    }
}
