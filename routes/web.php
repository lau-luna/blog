<?php

use App\Models\Category;
use PhpParser\Node\Expr\PostDec;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PruebasController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\ApiAuthMiddleware;

//Rutas del API
    // Rutas de prueba
    Route::get('/usuario/pruebas', [UserController::class, 'pruebas']);
    Route::get('/categoria/pruebas', [CategoryController::class, 'pruebas']);
    Route::get('/entrada/pruebas', [PostController::class, 'pruebas']);

    // Rutas del controlador de usuarios
    Route::withoutMiddleware(['web', 'VerifyCsrfToken'])->group(function () {
        Route::post('/api/register', [UserController::class, 'register']);
        Route::post('/api/login', [UserController::class, 'login']);
        Route::put('/api/user/update', [UserController::class, 'update']);
        Route::get('/api/user/avatar/{filename}', [UserController::class, 'getImage']);
    });
   

    Route::middleware([ApiAuthMiddleware::class])->group(function () {
        Route::post('/api/user/upload', [UserController::class, 'upload'])->withoutMiddleware(['web', 'VerifyCsrfToken']);
    });


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