<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\DemoLead;
use App\Models\DemoLeadEnquiry;
use App\Observers\DemoLeadEnquiryObserver;
use App\Observers\DemoLeadObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DemoLeadEnquiry::observe(DemoLeadEnquiryObserver::class);
        DemoLead::observe(DemoLeadObserver::class);

        View::composer('layouts.revamp', function ($view) {
            $view->with('revampFooterBlogs', Blog::where('status', 'ACTIVE')
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->take(2)
                ->get());
        });
    }
}
