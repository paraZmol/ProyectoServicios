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

        // logo por defecto y agregar nuevo logos
        $defaultLogoUrl = asset('img/logo_default.png');
        $setting = Setting::first();
        $logoPath = $setting ? $setting->logo_path : null;

        if ($logoPath && file_exists(storage_path('app/public/' . $logoPath))) {
            $logoUrl = asset('storage/' . $logoPath);
        } else {
            $logoUrl = $defaultLogoUrl;
        }

        // Aquí añadimos el dd() para ver el valor de la URL
        // dd($logoUrl);

        View::share('logoUrl', $logoUrl);

        // icono por defecto y agregar nuevos iconos
        $defaultFaviconUrl = asset('img/icon_default.png');
        $setting = Setting::first();
        $faviconPath = $setting ? $setting->favicon_path : null;

        if ($faviconPath && file_exists(storage_path('app/public/' . $faviconPath))) {
            $faviconUrl = asset('storage/' . $faviconPath);
        } else {
            $faviconUrl = $defaultFaviconUrl;
        }

        View::share('faviconUrl', $faviconUrl);
    }
}
