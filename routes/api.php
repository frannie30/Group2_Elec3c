<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\EcospaceController;

// API: check if an ecospace is open now
Route::get('/ecospace/open', [EcospaceController::class, 'openStatusApi']);
