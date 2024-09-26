<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function pruebas(Request $request) {
        return "Acción de pruebas de UserController";
    }

    public function register(Request $request) {

        $name = $request->input('name');
        $surname = $request->input('surname');

        return "Acción de registro de usuario ". $name . " " . $surname;
    }

    public function login(Request $request) {
        return "Acción de login de usuario";
    }   
}
