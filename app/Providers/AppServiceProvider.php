<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use App\Http\Middleware\CheckRole;

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
        // check role
        Route::aliasMiddleware('role', CheckRole::class);

        // nomnbre de empresa
        $setting = Setting::first();
        $companyNameString = $setting ? $setting->nombre_empresa : 'PROYECTO SERVICIOS S.A.';

        // para compartir el nombre de la empresa con otros archivos
        View::share('companyName', $companyNameString);

        // para poner el nombre de la empresa en la configuracion de laravel
        config(['app.name' => $companyNameString]);

        // logo de emrpesa por defecto
        $defaultLogoUrl = asset('img/logo_default.png');

        // obtener y compartir el logo
        $setting = Setting::first();
        $logoPath = $setting ? $setting->logo_path : null;

        if ($logoPath && file_exists(storage_path('app/public/' . $logoPath))) {
            // logo subido
            $logoUrl = asset('storage/' . $logoPath);
        } else {
            // local por defectro
            // logoUrl = $defaultLogoUrl;
            $logoUrl = $defaultLogoUrl;
        }

        View::share('logoUrl', $logoUrl);
    }
}
