<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route; // <-- Línea Añadida: Importar la fachada Route
use App\Models\Setting;
use App\Http\Middleware\CheckRole;    // <-- Línea Añadida: Importar tu Middleware

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

        View::share('companyName', $companyNameString);

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
            // logoUrl = $defaultLogoUrl; // Corregí esta línea en mi cabeza, si ya está definida arriba.
            $logoUrl = $defaultLogoUrl;
        }

        View::share('logoUrl', $logoUrl);
    }
}
