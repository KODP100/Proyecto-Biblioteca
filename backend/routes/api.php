<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DistritoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\EditorialController;
use App\Http\Controllers\EstadoLibroController;
use App\Http\Controllers\ExistenciaController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\MultaController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\SolicitanteController;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        
        Route::middleware('auth:api')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

     // CRUD de departamentos (protegido con JWT)
     Route::middleware('auth:api')->group(function () {
        Route::apiResource('departamentos', DepartamentoController::class);
        Route::apiResource('distritos', DistritoController::class);
        Route::apiResource('municipios', MunicipioController::class);
        Route::apiResource('empleados', EmpleadoController::class);
        Route::apiResource('categorias', CategoriaController::class);
        Route::apiResource('estadolibros', EstadoLibroController::class);
        Route::apiResource('editoriales', EditorialController::class);
        Route::apiResource('autores', AutorController::class);
        Route::apiResource('libros', LibroController::class);

        Route::apiResource('solicitantes', SolicitanteController::class);
        Route::apiResource('multas', MultaController::class);
        Route::apiResource('prestamos', PrestamoController::class);
        Route::apiResource('devoluciones', DevolucionController::class);
    });

});

Route::get('/', [AuthController::class, 'unauthorized'])->name('login');
