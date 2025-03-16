<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;
use App\Models\InventarisAJJ;
use App\Services\FileManagerServices;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FileManagerServices::class, function ($app) {
            return new FileManagerServices();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
{
}
}
