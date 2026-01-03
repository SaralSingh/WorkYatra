<?php
use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest Routes
Route::middleware('guest')->prefix('account')->group(function () {
    Route::get('register', [AccountController::class, 'registration'])->name('register.page');
    Route::post('process-register', [AccountController::class, 'processRegistration'])->name('register');

    Route::get('login', [AccountController::class, 'login'])->name('login');
    Route::post('process-login', [AccountController::class, 'authenticate'])->name('login.process');
});

// Authenticated Routes
Route::middleware('auth')->prefix('account')->group(function () {
    Route::get('profile', [AccountController::class, 'profile'])->name('profile.page');
    Route::get('post-job', [AccountController::class, 'postJob'])->name('post.job');
    Route::post('logout', [AccountController::class, 'logout'])->name('logout');
    Route::post('profile/update', [AccountController::class, 'updateProfile'])->name('update.profile');
    Route::post('profile-pic-update',[AccountController::class,'ProfilePicUpdate']);
});

Route::get('account/profile/update', function () {
    return redirect()->route('profile.page');
});
