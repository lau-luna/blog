<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function pruebas(Request $request) {
        return "Acción de pruebas de UserController";
    }

    public function register(Request $request) {

        // Recoger los datos del usuario por post

        // Validar datos

        // Cifrar la contraseña

        // Comprobar si el usuario ya existe (duplicado)

        // Crear el usuario

        $data = array(
            'status' => 'error',
            'code' => 404,
            'message' => 'El usuario no se ha creado'
        );

        return response()->json($data, $data['code']);
    }

    public function login(Request $request) {
        return "Acción de login de usuario";
    }   
}
