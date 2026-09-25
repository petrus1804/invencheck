<?php

namespace App\Providers;

use App\Models\StockTransaction;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.sidebar', function ($view) {
            if (auth()->check() && auth()->user()->hasPermission('approve_requests')) {
                $view->with('pendingCount', StockTransaction::where('status', 'pending')->count());
            }
        });
    }
}