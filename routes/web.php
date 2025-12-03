<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\EcospaceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\GuestlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProsAndConsController;
use App\Http\Middleware\CheckRole;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Redirect traditional auth page requests to the dashboard with a fragment
// so the site can show a CSS-only modal instead of a separate page.
Route::get('/login', function () {
    return redirect('/#login');
})->name('login');

Route::get('/register', function () {
    return redirect('/#register');
})->name('register');


// Public dashboard showing approved ecospaces (accessible to guests)
Route::get('/dashboard', [EcospaceController::class, 'dashboard'])->name('dashboard');

// Public 'Our Mission' page — accessible to guests and authenticated users
Route::get('/our-mission', [PageController::class, 'mission'])->name('mission');

// Public page that lists all ecospaces (separate from the dashboard)
Route::get('/events', [EventController::class, 'index'])->name('events.index');



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {


// Public users listing
Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::get('/ecospaces', [EcospaceController::class, 'all'])->name('ecospaces.index');

// Public events listing and full listing (preview at /events, full at /events/all)
Route::get('/events/all', [EventController::class, 'all'])->name('events.all');
// Short redirect for convenience: /all -> /events/all
Route::get('/all', function () {
    return redirect()->route('events.all');
})->name('events.all.short');
    // EcoSpace-facing (user)
    Route::get('/ecospace', [EcospaceController::class, 'showEcospace'])->name('ecospace');
    Route::get('/submitecospace', [EcospaceController::class, 'submitEcospace'])->name('submitecospace');
    Route::match(['get', 'post'], '/users/store', [EcospaceController::class, 'store'])->name('ecospaces.store');

    // Event-facing (user)
    Route::get('/submitevent', [EventController::class, 'submitEvent'])->name('submitevent');
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

    // Authenticated user's events listing (owners and regular users)
    Route::get('/my/events', [EventController::class, 'myEvents'])->name('my.events');

    // Owner edit routes for ecospaces and events (accessible to the owner user)
    Route::get('/user/ecospaces/{id}/edit', [EcospaceController::class, 'editOwner'])->name('user.ecospaces.edit');
    Route::put('/user/ecospaces/{id}', [EcospaceController::class, 'updateOwner'])->name('user.ecospaces.update');

    Route::get('/user/events/{id}/edit', [EventController::class, 'editOwner'])->name('user.events.edit');
    Route::put('/user/events/{id}', [EventController::class, 'updateOwner'])->name('user.events.update');

    

    // User profile page
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

    // Update user profile (image / basic fields) - owner only
    Route::post('/users/{id}/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');

    // Bookmark routes (toggle)
    Route::post('/bookmark/ecospace/{id}/toggle', [BookmarkController::class, 'toggleEcospace'])->name('bookmark.ecospace.toggle');
    Route::post('/bookmark/event/{id}/toggle', [BookmarkController::class, 'toggleEvent'])->name('bookmark.event.toggle');

    // Attendance / guestlist toggle
    Route::post('/events/{id}/attendance/toggle', [GuestlistController::class, 'toggle'])->name('events.attendance.toggle');

    // Reviews (ecospace & event)
    Route::post('/ecospace/{id}/reviews', [ReviewController::class, 'storeEcospace'])->name('ecospace.reviews.store');

    // Review form pages (GET)
    Route::get('/ecospace/{id}/reviews/create', [ReviewController::class, 'createEcospace'])->name('ecospace.reviews.create');
    // Choice page: pick between a full review or adding a pro/con
    Route::get('/ecospace/{id}/reviews/choose', [ReviewController::class, 'chooseEcospace'])->name('ecospace.reviews.choose');
    // Edit, update, delete reviews
    Route::get('/ecospace/{id}/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('ecospace.reviews.edit');
    Route::put('/ecospace/{id}/reviews/{review}', [ReviewController::class, 'update'])->name('ecospace.reviews.update');
    Route::delete('/ecospace/{id}/reviews/{review}', [ReviewController::class, 'destroy'])->name('ecospace.reviews.destroy');

    // Pros/Cons (ecospace)
    Route::get('/ecospace/{id}/proscons/create', [ProsAndConsController::class, 'create'])->name('ecospace.proscons.create');
    Route::post('/ecospace/{id}/proscons', [ProsAndConsController::class, 'store'])->name('ecospace.proscons.store');
    // Edit / update / delete for pros/cons
    Route::get('/ecospace/{id}/proscons/{pc}/edit', [ProsAndConsController::class, 'edit'])->name('ecospace.proscons.edit');
    Route::put('/ecospace/{id}/proscons/{pc}', [ProsAndConsController::class, 'update'])->name('ecospace.proscons.update');
    Route::delete('/ecospace/{id}/proscons/{pc}', [ProsAndConsController::class, 'destroy'])->name('ecospace.proscons.destroy');
 

    

    // Admin-only ecospace management
    Route::middleware(CheckRole::class)->group(function () {
        // Admin preview routes (controller methods) — use controller methods
        // so these can be promoted to full admin routes later.
        Route::get('/admin/ecospaces', [EcospaceController::class, 'adminEcospaces'])->name('admin.ecospaces');
        Route::get('/admin/events', [EventController::class, 'adminEvents'])->name('admin.events');
        // Separated admin create & archive pages
        Route::get('/admin/ecospaces/create', [EcospaceController::class, 'adminEcospacesCreate'])->name('admin.ecospaces.create');
        Route::get('/admin/ecospaces/archives', [EcospaceController::class, 'adminEcospacesArchives'])->name('admin.ecospaces.archives');
        Route::get('/admin/events/create', [EventController::class, 'adminEventsCreate'])->name('admin.events.create');
        Route::get('/admin/events/archives', [EventController::class, 'adminEventsArchives'])->name('admin.events.archives');

        Route::get('/create', [EcospaceController::class, 'create'])->name('create.index');
        Route::get('/index', [EcospaceController::class, 'index'])->name('index.index');

        // Admin user management (archive)
        Route::get('/admin/users', [\App\Http\Controllers\UserController::class, 'adminIndex'])->name('admin.users');
        Route::get('/admin/users/archives', [\App\Http\Controllers\UserController::class, 'archives'])->name('admin.users.archives');
        Route::post('/admin/users/{id}/archive', [\App\Http\Controllers\UserController::class, 'archive'])->name('admin.users.archive');
        Route::post('/admin/users/{id}/restore', [\App\Http\Controllers\UserController::class, 'restore'])->name('admin.users.restore');

    Route::post('/admin/approve/{id}', [EcospaceController::class, 'approve'])->name('admin.ecospace.approve');
    Route::post('/admin/recipe/{id}/remove', [EcospaceController::class, 'remove'])->name('admin.ecospace.remove');

    // Admin event moderation
    Route::post('/admin/event/{id}/approve', [EventController::class, 'approve'])->name('admin.event.approve');
    Route::post('/admin/event/{id}/remove', [EventController::class, 'remove'])->name('admin.event.remove');
    // Confirmation pages (no JS) for deleting/removing events
    Route::get('/admin/event/{id}/confirm-remove', [EventController::class, 'confirmRemove'])->name('admin.event.confirm-remove');
    Route::get('/admin/events/{id}/confirm-delete', [EventController::class, 'confirmDelete'])->name('admin.events.confirm-delete');
    Route::get('/admin/event/{id}/edit', [EventController::class, 'edit'])->name('admin.event.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::post('/admin/events/{id}/restore', [EventController::class, 'restore'])->name('admin.events.restore');
    Route::post('/admin/events/{id}/delete', [EventController::class, 'delete'])->name('admin.events.delete');

    // Confirmation pages for ecospace delete/remove
    Route::get('/admin/ecospace/{id}/confirm-remove', [EcospaceController::class, 'confirmRemove'])->name('admin.ecospace.confirm-remove');
    Route::get('/admin/ecospaces/{id}/confirm-delete', [EcospaceController::class, 'confirmDelete'])->name('admin.ecospaces.confirm-delete');

        Route::get('/admin/recipe/{id}/edit', [EcospaceController::class, 'edit'])->name('edit.index');
    Route::put('/recipes/{id}', [EcospaceController::class, 'update'])->name('ecospaces.update');

        Route::get('/archives', [EcospaceController::class, 'archives'])->name('archives.index');
    Route::post('/admin/recipes/{id}/restore', [EcospaceController::class, 'restore'])->name('admin.ecospaces.restore');
    Route::post('/admin/recipes/{id}/delete', [EcospaceController::class, 'delete'])->name('admin.ecospaces.delete');
    });
});