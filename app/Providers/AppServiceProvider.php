<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

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
        $setting = Setting::first();
        $companyNameString = $setting ? $setting->nombre_empresa : 'PROYECTO SERVICIOS S.A.';

        View::share('companyName', $companyNameString);
    }
}
