<?php

use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

Route::post('/tickets', [TicketController::class, 'store']);

Route::get('/tickets/statistics', [StatisticsController::class, 'index']);
