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
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
});

// acceso a todos lo logeados
Route::middleware('auth')->group(function(){
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// admin y papelera
Route::middleware(['auth', 'role:admin'])->group(function () {
    // usuarios espesificos
    Route::get('/users/deleted', [UserController::class, 'deleted'])->name('users.deleted');
    Route::put('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');
    Route::resource('users', UserController::class);

    // boeltas espesificas
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy'); // Destroy manual si no está en resource
    Route::get('/invoices/deleted', [InvoiceController::class, 'deleted'])->name('invoices.deleted');
    Route::put('/invoices/{id}/restore', [InvoiceController::class, 'restore'])->name('invoices.restore');
    Route::delete('/invoices/{id}/force-delete', [InvoiceController::class, 'forceDelete'])->name('invoices.forceDelete');

    // papelera de clientes
    Route::get('/clients/deleted', [ClientController::class, 'deleted'])->name('clients.deleted');
    Route::put('/clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
    Route::delete('/clients/{id}/force-delete', [ClientController::class, 'forceDelete'])->name('clients.forceDelete');

    //papelera de clientes
    Route::get('/services/deleted', [ServiceController::class, 'deleted'])->name('services.deleted');
    Route::put('/services/{id}/restore', [ServiceController::class, 'restore'])->name('services.restore');
    Route::delete('/services/{id}/force-delete', [ServiceController::class, 'forceDelete'])->name('services.forceDelete');

    //configuracion para solo el admin
    Route::get('configuracion', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('configuracion', [SettingController::class, 'update'])->name('settings.update');
});

// trabajador y admin en gestion operavita --- genericos
Route::middleware(['auth', 'role:admin|trabajador'])->group(function () {

    // crud servicios
    Route::resource('services', ServiceController::class)->except(['index', 'show']);

    //clientes
    Route::get('/clients/ajax-search', [ClientController::class, 'searchAjax'])->name('clients.ajax.search');
    Route::resource('clients', ClientController::class);

    //boletas
    Route::resource('invoices', InvoiceController::class)->except(['destroy']);
    Route::get('invoices/{invoice}/ticket', [InvoiceController::class, 'ticket'])->name('invoices.ticket');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
});

// user trabajador y admin
Route::middleware(['auth', 'role:admin|trabajador|usuario'])->group(function () {
    // solo lectura para serviicos
    Route::resource('services', ServiceController::class)->only(['index', 'show']);
});

require __DIR__.'/auth.php';
