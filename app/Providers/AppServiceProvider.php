<?php

namespace App\Providers;

use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use App\Observers\DashboardCacheObserver;
use Illuminate\Database\Eloquent\Model;
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
        // In local (non-test) environment, log a warning on lazy loads to catch N+1 issues.
        // We don't throw exceptions in tests because some legacy test fixtures rely on lazy loading.
        if (app()->isLocal() && !app()->runningUnitTests()) {
            Model::preventLazyLoading();
            Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
                $class = get_class($model);
                logger()->warning("N+1 detected: {$class}::{$relation} was lazy-loaded. Add eager loading.");
            });
        }

        // Flush admin dashboard stats & public cache when relevant models change
        User::observe(DashboardCacheObserver::class);
        Enrollment::observe(DashboardCacheObserver::class);
        Payment::observe(DashboardCacheObserver::class);
        \App\Models\Program::observe(DashboardCacheObserver::class);
        \App\Models\ClassRoom::observe(DashboardCacheObserver::class);
    }
}
