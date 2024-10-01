<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use App\Models\User;

class JwtAuth {

    public $key;

    public function __construct()
    {
        $this->key = 'esto_es_una_clave_super_secreta-298765171';
    }
    
    public function signup($email, $password, $getToken = null) {
        // Buscar si existe el usuario con su email
        $user = User::where('email', $email)->first();

        // Comprobar si el usuario existe y la contraseña es correcta
        if (is_object($user) && password_verify($password, $user->password)) {
            // Generar el token con los datos del usuario identificado
            $token = array(
                'sub'       =>      $user->id,
                'email'     =>      $user->email,
                'name'      =>      $user->name,
                'surname'   =>      $user->surname,
                'iat'       =>      time(),
                'exp'       =>      time() +  (7 * 24 * 60 * 60) // 1 semana
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, array('HS256'));

            // Devolver los datos decodificados o el token, en función de un parámetro
            if (is_null($getToken)) {
                $data = $jwt;
            } else {
                $data = $decoded;
            }
        } else {
            // Login incorrecto
            $data = array(
                'status'    =>  'error',
                'message'   =>  'Login incorrecto'
            );
        }

        return $data;
    }
}