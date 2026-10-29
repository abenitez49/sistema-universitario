<?php



use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MateriaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AlumnoMateriaController;
use App\Http\Controllers\MateriaProfesorController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\AsistenciaController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas protegidas por autenticación general
Route::middleware(['auth'])->group(function () {
    
    // Solo Administradores pueden gestionar materias y personal
    Route::middleware(['role:Administrador'])->group(function () {
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

    // Profesores y Administradores pueden ver/llamar lista
    Route::middleware(['role:Administrador|Profesor'])->group(function () {
        Route::get('/asistencia/{materia}', [AsistenciaController::class, 'index'])->name('asistencia.index');
        Route::post('/asistencia/{materia}', [AsistenciaController::class, 'store'])->name('asistencia.store');
        Route::get('asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
        Route::get('asistencias/{materia}', [AsistenciaController::class, 'show'])->name('asistencias.show');
        Route::post('asistencias/{materia}', [AsistenciaController::class, 'store'])->name('asistencias.store');
    });
});



require __DIR__.'/auth.php';