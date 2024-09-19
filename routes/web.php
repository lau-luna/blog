<?php

use App\Http\Controllers\PruebasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pruebas/{nombre?}', function($nombre = null ) {

    $texto = '<h2>Texto desde una ruta</h2>';
    $texto .= 'Nombre: '.$nombre;

    return view('pruebas', array (
        'texto' => $texto
    ));
});

Route::get('/animales', [PruebasController::class, 'index']);

Route::get('/test-orm', [PruebasController::class, 'testOrm']);