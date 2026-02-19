<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('patients.index');
    }

    return redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('patients', [PatientController::class, 'index'])->name('patients.index');

    Route::middleware(['role:admin'])->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
    Route::get('patients/export', [PatientController::class, 'export'])
        ->middleware('role:admin')
        ->name('patients.export');
    Route::get('patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
});

require __DIR__.'/settings.php';
