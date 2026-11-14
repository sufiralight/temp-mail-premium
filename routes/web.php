<?php

use App\Http\Controllers\AdminConsoleController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\InstallerController;
use App\Installation\InstallationGate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Route::get('/', function (Request $request, InstallationGate $gate) {
    try {
        $gate->authorize($request);
    } catch (NotFoundHttpException) {
        return config('app.key') ? view('welcome') : response('The deployment operator must restore configuration or enable installation.', 503);
    }

    return redirect(route('install.database', absolute: false));
});

Route::prefix('install')->name('install.')->group(function (): void {
    Route::get('/', [InstallerController::class, 'show'])->name('database');
    Route::post('/', [InstallerController::class, 'verify'])->middleware('throttle:6,1')->name('verify');
    Route::get('/requirements', [InstallerController::class, 'requirements'])->name('requirements');
});

Route::middleware('guest')->group(function (): void {
    Route::view('/login', 'auth.form', ['screen' => 'login'])->name('login');
    Route::post('/login', [AuthenticationController::class, 'login'])->middleware('throttle:6,1');
    Route::view('/register', 'auth.form', ['screen' => 'register'])->name('register');
    Route::post('/register', [AuthenticationController::class, 'register'])->middleware('throttle:6,1');
    Route::view('/forgot-password', 'auth.form', ['screen' => 'forgot'])->name('password.request');
    Route::post('/forgot-password', [AuthenticationController::class, 'sendResetLink'])->middleware('throttle:5,1');
    Route::get('/reset-password/{token}', fn (string $token) => response()
        ->view('auth.form', ['screen' => 'reset', 'token' => $token])
        ->header('Cache-Control', 'no-store')
        ->header('Referrer-Policy', 'no-referrer'))->where('token', '[a-f0-9]{64}')->name('password.reset');
    Route::post('/reset-password', [AuthenticationController::class, 'reset'])->middleware('throttle:6,1');
});

Route::middleware(['auth', 'auth.session'])->group(function (): void {
    Route::view('/account', 'auth.account')->name('dashboard');
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'auth.session', 'can:admin.access'])->group(function (): void {
    Route::get('/', [AdminConsoleController::class, 'dashboard'])->name('overview');
    Route::get('/users', [AdminConsoleController::class, 'users'])->name('users');
});
