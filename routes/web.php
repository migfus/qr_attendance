<?php

// phpinfo();
use Illuminate\Support\Facades\Route;

// SECTION: PAGES
use App\Http\Controllers\{
    AuthController,
    QRCodeController,
    ArtaIDController,
    FeedbackController,
    SetupController,
};

// SECTION: DASHBOARD
use App\Http\Controllers\dashboard\{
    AccountSettingsController,
    ArtaIdDashboardController,
    DashboardController,
    DataConfigurationController,
    DepartmentController,
    GalleryController,
    NotificationController,
    GuestQRController,
    RequestStatusController,
    UserController,
    RolesPermissionsController,
    GeminiController
};

// SECTION: REDIRECT
use App\Http\Controllers\api\{GuestQrCodeController};

Route::name('setup.')->group(function () {
    Route::get('/setup', function () {
        return to_route('setup.show', 1);
    });
    Route::resource('/setup', SetupController::class)
        ->only(['show', 'store'])
        ->names(['show' => 'show', 'store' => 'store']);
});


// NOTE: PAGES
Route::redirect('/', 'arta-id')->name('index');
Route::resource('/guest-qr-codes', QRCodeController::class)
    ->only(['store', 'destroy', 'index']);
Route::resource('/arta-id', ArtaIDController::class)
    ->only(['index', 'store', 'show', 'edit', 'destroy', 'update'])
    ->whereUlid('arta_id');
Route::resource('/feedbacks', FeedbackController::class)->only(['index']);

// SECTION: REDIRECT
Route::get('/l/{name}', [GuestQrCodeController::class, 'show'])
    ->name('link.show');
Route::get('/qr/{id}', [QRCodeController::class, 'redirect'])
    ->name('qr.redirect');

// NOTE: AUTH
Route::get('/login', [AuthController::class, 'login'])
    ->name('login')
    ->middleware(['guest']); // NOTE: Prevents authenticated users from accessing the login page
Route::post('/login', [AuthController::class, 'loginSubmit'])
    ->name('login.submit');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::resource('/', DashboardController::class)
            ->only(['index']);
        Route::resource('/guest-qr', GuestQRController::class)
            ->except(['show']);
        Route::resource('/request-statuses', RequestStatusController::class)
            ->only(['index', 'update']);

        Route::get('/arta-id/prints', [ArtaIdDashboardController::class, 'prints'])->name('arta_id.prints');
        Route::resource('/arta-id', ArtaIdDashboardController::class)
            ->only(['index', 'show', 'update', 'edit', 'destroy']);

        Route::get('/users/prints', [UserController::class, 'prints'])->name('users.prints');
        Route::resource('/users', UserController::class)
            ->except(['show']);
        Route::resource('/galleries', GalleryController::class)
            ->only(['index']);
        Route::resource('/departments', DepartmentController::class);
        Route::resource('/data-configurations', DataConfigurationController::class)
            ->except(['edit', 'create',]);

        Route::resource('/notifications', NotificationController::class)
            ->only(['index', 'store', 'update']);
        Route::resource('/roles-permissions', RolesPermissionsController::class);
        Route::resource('/account-settings', AccountSettingsController::class)
            ->only(['show', 'store']);

        Route::resource('gemini', GeminiController::class)->only(['index', 'store', 'destroy']);
    });
});



/* WARNING
UPON UPDATING THE ROUTE PLEASE UPDATE THE ZIGGY PATH
use command: php artisan ziggy:generate --types
REASONS: The front-end is using route() for easy navigation and typescript friendly.
*/
