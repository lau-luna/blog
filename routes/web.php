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
    Route::get('/usuario/pruebas', [Usercontroller::class, 'pruebas']);
    Route::get('/categoria/pruebas', [CategoryController::class, 'pruebas']);
    Route::get('/entrada/pruebas', [PostController::class, 'pruebas']);

    // Rutas del controlador de usuarios
    Route::withoutMiddleware(['web', 'VerifyCsrfToken'])->group(function () {
        Route::post('/api/register', [UserController::class, 'register']);
        Route::post('/api/login', [UserController::class, 'login']);
        Route::put('/api/user/update', [UserController::class, 'update']);
        Route::get('/api/user/avatar/{filename}', [UserController::class, 'getImage']);
        Route::get('/api/user/detail/{id}', [UserController::class, 'detail']);
    });

    Route::middleware([ApiAuthMiddleware::class])->group(function () {
        Route::post('/api/user/upload', [UserController::class, 'upload'])->withoutMiddleware(['web', 'VerifyCsrfToken']);
    });


    
    //  Rutas del controlador de categorias
    Route::middleware([ApiAuthMiddleware::class])->group(function () {
        // Rutas con ApiAuthMiddleware para todas excepto index y show
        Route::resource('/api/category', CategoryController::class)->except(['index', 'show'])->withoutMiddleware(['web', 'VerifyCsrfToken']);
    });
    // Rutas sin ApiAuthMiddleware para index y show
    Route::get('/api/category', [CategoryController::class, 'index']);
    Route::get('/api/category/{id}', [CategoryController::class, 'show']);


    // Rutas del controlador de entradas
    Route::middleware([ApiAuthMiddleware::class])->group((function () {
        Route::resource('/api/post', PostController::class)->except(['index', 'show'])->withoutMiddleware(['web', 'VerifyCsrfToken']);
        Route::post('/api/post/upload', [PostController::class, 'upload'])->withoutMiddleware(['web', 'VerifyCsrfToken']);
    }));
    // Rutas sin ApiAuthMiddleware para index y show
    Route::get('/api/post', [PostController::class, 'index']);
    Route::get('/api/post/{id}', [PostController::class, 'show']);
    Route::get('/api/post/image/{filename}', [PostController::class, 'getImage'])->withoutMiddleware(['web', 'VerifyCsrfToken']);

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