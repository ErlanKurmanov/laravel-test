<?php

use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\WidgetController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/widget', [WidgetController::class, 'show'])->name('widget.show');

Route::get('/dashboard', function () {
    return redirect()->route('admin.tickets.index');
})->middleware('auth')->name('dashboard');

Route::get('/', function () {
    return redirect()->route('admin.tickets.index');
});
Route::prefix('admin')
//    ->middleware(['auth', 'role:manager'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {return redirect()->route('admin.tickets.index');})->name('dashboard');
        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
        Route::patch('/tickets/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    });
