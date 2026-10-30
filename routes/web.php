<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\MateriaProfesorController;
use App\Http\Controllers\AlumnoMateriaController;
use App\Http\Controllers\AsistenciaController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Profesor;
use App\Models\Materia;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // DASHBOARD Y PERFIL (Accesible por Todos: Administrador, Administrativo, Profesor, Alumno)
    Route::get('/dashboard', function () {
        $totales = [
            'usuarios' => User::count(),
            'alumnos' => Alumno::count(),
            'profesores' => Profesor::count(),
            'materias' => Materia::count(),
        ];
        return view('dashboard', compact('totales'));
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // MÓDULOS EXCLUSIVOS (Solo Administrador y Administrativo)
    Route::group(['middleware' => ['role:Administrador|Administrativo']], function () {
        Route::resource('materias', MateriaController::class);
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('alumnos', AlumnoController::class);
        Route::resource('profesores', ProfesorController::class);

        Route::get('asignar-profesores', [MateriaProfesorController::class, 'index'])->name('asignar-profesores.index');
        Route::post('asignar-profesores/{materia}', [MateriaProfesorController::class, 'store'])->name('asignar-profesores.store');
        Route::delete('asignar-profesores/{materia}/{profesor}', [MateriaProfesorController::class, 'destroy'])->name('asignar-profesores.destroy');

        Route::get('matricular-alumnos', [AlumnoMateriaController::class, 'index'])->name('matricular-alumnos.index');
        Route::post('matricular-alumnos/{materia}', [AlumnoMateriaController::class, 'store'])->name('matricular-alumnos.store');
        Route::delete('matricular-alumnos/{materia}/{alumno}', [AlumnoMateriaController::class, 'destroy'])->name('matricular-alumnos.destroy');
    });

    // MÓDULOS DE AULA (Administrador, Administrativo Y Profesores)
    Route::group(['middleware' => ['role:Administrador|Administrativo|Profesor']], function () {
        Route::get('asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
        Route::get('asistencias/{materia}', [AsistenciaController::class, 'show'])->name('asistencias.show');
        Route::post('asistencias/{materia}', [AsistenciaController::class, 'store'])->name('asistencias.store');
    });

});

require __DIR__.'/auth.php';