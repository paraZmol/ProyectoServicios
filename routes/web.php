<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    if (Auth::check()) {
        //logeado
        return redirect()->route('dashboard');
    } else {
        // al login
        return redirect()->route('login');
    }
});

// acceso para todos
Route::middleware('auth')->group(function(){
    // perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// solo admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    // gestion de usuarios
    Route::resource('users', UserController::class); // Esta puede ir aquí

    // clientes eliminados
    Route::get('/clients/deleted', [ClientController::class, 'deleted'])
         ->name('clients.deleted');
    Route::put('/clients/{id}/restore', [ClientController::class, 'restore'])
         ->name('clients.restore');

    // servicios eliminados
    Route::get('/services/deleted', [ServiceController::class, 'deleted'])
         ->name('services.deleted');
    Route::put('/services/{id}/restore', [ServiceController::class, 'restore'])
         ->name('services.restore');

    // boletas eliminadas
    Route::get('/invoices/deleted', [InvoiceController::class, 'deleted'])
         ->name('invoices.deleted');
    Route::put('/invoices/{id}/restore', [InvoiceController::class, 'restore'])
         ->name('invoices.restore');
});

// admin trabajador y usuario
Route::middleware(['auth', 'role:admin|trabajador|usuario'])->group(function () {
    // RUTA GENÉRICA DE SERVICIOS
    Route::resource('services', ServiceController::class);
});

// admin y trabajador
Route::middleware(['auth', 'role:admin|trabajador'])->group(function () {
    // rutas genericas
    Route::resource('clients', ClientController::class);
    Route::resource('invoices', InvoiceController::class);

    // setting
    Route::get('configuracion', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('configuracion', [SettingController::class, 'update'])->name('settings.update');

    // pdf
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
});


require __DIR__.'/auth.php';