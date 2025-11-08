<?php

namespace App\Providers;

use App\Interfaces;
use App\Repositories;
use App\Services;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(Interfaces\CustomerRepositoryInterface::class, Repositories\EloquentCustomerRepository::class);
        $this->app->bind(Interfaces\TicketRepositoryInterface::class, Repositories\EloquentTicketRepository::class);

        $this->app->bind(Interfaces\TicketServiceInterface::class, Services\TicketService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
