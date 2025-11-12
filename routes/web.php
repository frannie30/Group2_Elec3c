<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\EcospaceController;
use App\Http\Controllers\EventController;
use App\Http\Middleware\CheckRole;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contactus', [GuestController::class, 'contactus'])->name('contactus.index');
Route::get('/privacy', [GuestController::class, 'privacy'])->name('privacy.index');
Route::get('/creator', [GuestController::class, 'creator'])->name('creator.index');
Route::get('/website', [GuestController::class, 'website'])->name('website.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard shows approved ecospaces
    Route::get('/dashboard', [EcospaceController::class, 'dashboard'])->name('dashboard');

    // EcoSpace-facing (user)
    Route::get('/ecospace', [EcospaceController::class, 'showEcospace'])->name('ecospace');
    Route::get('/submitecospace', [EcospaceController::class, 'submitEcospace'])->name('submitecospace');
    Route::match(['get', 'post'], '/users/store', [EcospaceController::class, 'store'])->name('ecospaces.store');

    // Event-facing (user)
    Route::get('/submitevent', [EventController::class, 'submitEvent'])->name('submitevent');
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

    // Admin-only ecospace management
    Route::middleware(CheckRole::class)->group(function () {
        Route::get('/create', [EcospaceController::class, 'create'])->name('create.index');
        Route::get('/index', [EcospaceController::class, 'index'])->name('index.index');

    Route::post('/admin/approve/{id}', [EcospaceController::class, 'approve'])->name('admin.ecospace.approve');
    Route::post('/admin/recipe/{id}/remove', [EcospaceController::class, 'remove'])->name('admin.ecospace.remove');

    // Admin event moderation
    Route::post('/admin/event/{id}/approve', [EventController::class, 'approve'])->name('admin.event.approve');
    Route::post('/admin/event/{id}/remove', [EventController::class, 'remove'])->name('admin.event.remove');
    Route::get('/admin/event/{id}/edit', [EventController::class, 'edit'])->name('admin.event.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::post('/admin/events/{id}/restore', [EventController::class, 'restore'])->name('admin.events.restore');
    Route::post('/admin/events/{id}/delete', [EventController::class, 'delete'])->name('admin.events.delete');

        Route::get('/admin/recipe/{id}/edit', [EcospaceController::class, 'edit'])->name('edit.index');
    Route::put('/recipes/{id}', [EcospaceController::class, 'update'])->name('ecospaces.update');

        Route::get('/archives', [EcospaceController::class, 'archives'])->name('archives.index');
    Route::post('/admin/recipes/{id}/restore', [EcospaceController::class, 'restore'])->name('admin.ecospaces.restore');
    Route::post('/admin/recipes/{id}/delete', [EcospaceController::class, 'delete'])->name('admin.ecospaces.delete');
    });
});