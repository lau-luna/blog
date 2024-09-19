<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class PruebasController extends Controller
{
    public function index() {
        $titulo = 'Animales';

        $animales = ['Perro', 'Gato', 'Tigre'];

        return view('pruebas.index', array(
            'titulo' => $titulo,
            'animales' => $animales
        ));
    }

    public function testOrm(){
        $post = Post::all();
        var_dump($post);
        die();
    }
}
