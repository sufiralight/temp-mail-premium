<?php

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
