<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
        // Registrar middleware de roles
        Route::aliasMiddleware('role', CheckRole::class);

        // obtener configuracion
        $setting = Setting::first();

        // configurar nombre de empresa
        $companyNameString = $setting ? $setting->nombre_empresa : 'PROYECTO SERVICIOS S.A.';

        // compartir con todas las vistas
        View::share('companyName', $companyNameString);

        // actrualizar la configuracion de laravel
        config(['app.name' => $companyNameString]);

        // confuguracion de logo de empresa
        $defaultLogoUrl = asset('img/logo_default.png');
        $logoPath = $setting ? $setting->logo_path : null;

        // cambio a storage disk
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            // storage link
            $logoUrl = Storage::url($logoPath);
        } else {
            $logoUrl = $defaultLogoUrl;
        }

        View::share('logoUrl', $logoUrl);

        // icono por defecto
        $defaultFaviconUrl = asset('img/icon_default.png');
        $faviconPath = $setting ? $setting->favicon_path : null;

        // disk
        if ($faviconPath && Storage::disk('public')->exists($faviconPath)) {
            // url
            $faviconUrl = Storage::url($faviconPath);
        } else {
            $faviconUrl = $defaultFaviconUrl;
        }

        View::share('faviconUrl', $faviconUrl);

        // compartir ocmpleto
        View::share('setting', $setting);
    }
}
