<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\MenorController;
use App\Http\Controllers\AdminExportController; 

// Página de bienvenida
Route::get('/', [InscripcionController::class, 'bienvenida'])
    ->name('inscripcion.bienvenida');

// Formulario de inscripción de tutor (usuario + embarazo)
Route::get('/formulario', [InscripcionController::class, 'formulario'])
    ->name('inscripcion.formulario');

// Guardar tutor (usuario + embarazo)
Route::post('/formulario', [InscripcionController::class, 'guardar'])
    ->name('inscripcion.guardar');

// Formulario para agregar menor(es) ligado(s) a un tutor (usa hash público)
Route::get('/menor/{hash}', [MenorController::class, 'formulario'])
    ->name('menor.formulario');

// Guardar menor ligado a tutor (usa hash público)
Route::post('/menor/{hash}', [MenorController::class, 'guardar'])
    ->name('menor.guardar');

// Página de despedida, parámetro hash opcional
Route::get('/despedida/{hash?}', [InscripcionController::class, 'despedida'])
    ->name('inscripcion.despedida');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->group(function () {
        Route::get('/', [InscripcionController::class, 'index'])
            ->name('admin.index');

        Route::put('/{usuario}', [InscripcionController::class, 'update'])
            ->name('admin.update');

        Route::delete('/{usuario}', [InscripcionController::class, 'destroy'])
            ->name('admin.destroy');

        Route::put('/menores/{menor}', [MenorController::class, 'update'])
            ->name('menores.update');

        Route::delete('/menores/{menor}', [MenorController::class, 'destroy'])
            ->name('menores.destroy');
    });
});

require __DIR__.'/auth.php';
