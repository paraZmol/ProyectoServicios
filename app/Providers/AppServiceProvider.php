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
        //$setting = null;

        // configurar nombre de empresa
        $companyNameString = $setting ? $setting->nombre_empresa : 'PROYECTO SERVICIOS S.A.';

        // compartir con todas las vistas
        View::share('companyName', $companyNameString);

        // actrualizar la configuracion de laravel
        config(['app.name' => $companyNameString]);

        // confuguracion de logo de empresa
        $defaultLogoUrl = asset('img/logo_default.png');
        // Ruta base absoluta para la imagen por defecto (útil para PDF base64)
        $defaultLogoAbsolutePath = public_path('img/logo_default.png');
        
        $logoPath = $setting ? $setting->logo_path : null;

        // variables para la vista web y para el PDF base64
        $logoUrl = $defaultLogoUrl;
        $logoAbsolutePath = $defaultLogoAbsolutePath;

        // cambio a storage disk
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            // para vistas web normales
            $logoUrl = Storage::url($logoPath);
            // para el generador PDF que falla con rutas de Windows
            $logoAbsolutePath = Storage::disk('public')->path($logoPath);
        }

        // Compartir URL normal para webs
        View::share('logoUrl', $logoUrl);

        // Convertir la imagen final (subida o defecto) a Base64 para inyectar directo al HTML del PDF
        $pdfLogoBase64 = '';
        if (file_exists($logoAbsolutePath)) {
            $type = pathinfo($logoAbsolutePath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoAbsolutePath);
            $pdfLogoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        View::share('pdfLogoBase64', $pdfLogoBase64);

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
