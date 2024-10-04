<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;

class PostController extends Controller
{   

    public function index() {
        $posts = Post::all()->load('category');

        return response()->json([
            'code'      => 200,
            'status'    => 'success',
            'posts'     => $posts
        ], 200);
    }

    public function show($id) {
        $post = Post::find($id)->load('category');

        if(is_object($post)) {
            $data = [
                'code'      => 200,
                'status'    => 'success',
                'post'      => $post
            ];
        }else{
            $data = [
                'code'      => 404,
                'status'    => 'error',
                'message'      => 'La entrada no existe.'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function store(Request $request){
        // Obtener datos
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        // Chequear si no está vacío
        if ($params_array) {
            // Validación de datos
            $validate = Validator::make($params_array, [
                'title'     => 'required',
                'content'   => 'required',
                
            ]);

        }else{
            $data = [
                'code'      => 400,
                'status'    => 'error',
                'message'      => 'No has enviado ningun post.'
            ];
        }
    }
}
