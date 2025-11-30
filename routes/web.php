<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use PhpParser\Builder\Function_;

Route::get('/', function () {
    if (Auth::check()) {
        //logeado
        return redirect()->route('dashboard');
    } else {
        // al login
        return redirect()->route('login');
    }
});

/*Route::middleware('auth')->group(function () {
    // perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // serivioc - cliente - boleta
    Route::resource('services', ServiceController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('invoices', InvoiceController::class);

    // configuracion
    Route::get('configuracion', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('configuracion', [SettingController::class, 'update'])->name('settings.update');

    // pdf
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');

    // dash
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});*/

// acceso para todos
Route::middleware('auth')->group(function(){
    // perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// admin trabajador y usuario
Route::middleware(['auth', 'role:admin|trabajador|usuario'])->group(function () {
    Route::resource('services', ServiceController::class);
});

// admin y trabajador
Route::middleware(['auth', 'role:admin|trabajador'])->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('invoices', InvoiceController::class);

    // setting
    Route::get('configuracion', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('configuracion', [SettingController::class, 'update'])->name('settings.update');

    // pdf
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
});

// solo admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
