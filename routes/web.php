<?php

use App\Models\Category;
use PhpParser\Node\Expr\PostDec;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PruebasController;
use App\Http\Controllers\CategoryController;

//Rutas del API
    // Rutas de prueba
    Route::get('/usuario/pruebas', [UserController::class, 'pruebas']);
    Route::get('/categoria/pruebas', [CategoryController::class, 'pruebas']);
    Route::get('/entrada/pruebas', [PostController::class, 'pruebas']);

    // Rutas del controlador de usuarios
    Route::post('/api/register', [UserController::class, 'register'])->withoutMiddleware(['web', 'VerifyCsrfToken']);
    Route::post('/api/login', [UserController::class, 'login'])->withoutMiddleware(['web', 'VerifyCsrfToken']);





Route::get('/', function () {
    return view('welcome');
});

/* Rutas de prueba 

Route::get('/pruebas/{nombre?}', function($nombre = null ) {

    $texto = '<h2>Texto desde una ruta</h2>';
    $texto .= 'Nombre: '.$nombre;

    return view('pruebas', array (
        'texto' => $texto
    ));
});

Route::get('/animales', [PruebasController::class, 'index']);

Route::get('/test-orm', [PruebasController::class, 'testOrm']);
*/