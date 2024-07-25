<?php

namespace App\Providers;

use App\Models\City;
use App\Models\User;
use App\Models\State;
use App\Models\Address;
use App\Services\AddressService;
use App\Services\SinglePassService;
use App\Http\Repository\CityRepository;
use App\Http\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Http\Repository\StateRepository;
use App\Http\Repository\AddressRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Http\Repository\AutoRepairRepository;
use App\Interfaces\AutoRepairRepositoryInterface;
use App\Models\AutoRepair;
use App\Models\AutoRepairUSer;
use App\Services\AccessService;
use App\Services\EmailAdapterImplements;
use App\Services\RdStationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AutoRepairRepositoryInterface::class, function($app){
            return new AutoRepairRepository(
                $app->make(AutoRepair::class),
                $app->make(AutoRepairUSer::class)
            );
        });

        $this->app->bind('StateRepository', function($app){
            return new StateRepository($app->make(State::class));
        });

        $this->app->bind('CityRepository', function($app){
            return new CityRepository($app->make(City::class));
        });

        $this->app->bind('AddressRepository', function($app){
            return new AddressRepository($app->make(Address::class));
        });

        $this->app->bind(AddressService::class, function($app){
            return new AddressService(
                $app->make('StateRepository'),
                $app->make('CityRepository'),
                $app->make('AddressRepository')
            );
        });

        $this->app->bind(UserRepositoryInterface::class, function($app){
            return new UserRepository($app->make(User::class));
        });

        $this->app->singleton(SinglePassService::class, function(){
            return new SinglePassService();
        });

        $this->app->bind('AccessService', function($app){
            return new AccessService(
                $app->make(AddressService::class),
                $app->make(SinglePassService::class),
                $app->make(UserRepositoryInterface::class),
                $app->make(AutoRepairRepositoryInterface::class),
            );
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
