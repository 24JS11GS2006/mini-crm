<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TicketController;

// root redirect to clients list (so visiting / goes to /clients)
Route::get('/', function () {
    return redirect()->route('clients.index');
});

// Resource routes (public for now)
Route::resource('clients', ClientController::class);
Route::resource('tickets', TicketController::class);

// Endpoint optional para cambiar estado (usa controlador con método changeStatus)
Route::post('tickets/{ticket}/change-status', [TicketController::class, 'changeStatus'])->name('tickets.changeStatus');