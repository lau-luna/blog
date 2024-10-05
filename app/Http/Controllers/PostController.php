<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use App\Helpers\JwtAuth;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::all()->load('category');

        return response()->json([
            'code'      => 200,
            'status'    => 'success',
            'posts'     => $posts
        ], 200);
    }

    public function show($id)
    {
        $post = Post::find($id)->load('category');

        if (is_object($post)) {
            $data = [
                'code'      => 200,
                'status'    => 'success',
                'post'      => $post
            ];
        } else {
            $data = [
                'code'      => 404,
                'status'    => 'error',
                'message'      => 'La entrada no existe.'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {
        // Obtener datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        // Chequear si no está vacío
        if (!empty($params_array)) {
            // Conseguir usuario identificado
            $jwtAuth = new JwtAuth();
            $token = $request->header('Authorization', null);
            $user = $jwtAuth->checkToken($token, true);

            // Validación de datos
            $validate = Validator::make($params_array, [
                'title'       => 'required',
                'content'     => 'required',
                'category_id' => 'required',
                'image'       => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'code'  => 400,
                    'status' => 'error',
                    'message' => 'No se ha guardado el post, faltan datos.'
                ];
            } else {
                // Guardar el artículo
                $post = new Post();
                $post->user_id = $user->sub;
                $post->category_id = $params->category_id;
                $post->title = $params->title;
                $post->content = $params->content;
                $post->image = $params->image;

                $post->save();

                $data = [
                    'code'      => 200,
                    'status'    => 'success',
                    'post'      => $post
                ];
            }
        } else {
            $data = [
                'code'      => 400,
                'status'    => 'error',
                'message'      => 'No has enviado ningun post.'
            ];
        }

        // Devolver respuesta
        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request)
    {
        // Recoger datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        $data = [
            'code'      => 404,
            'status'    => 'error',
            'message'   => 'Datos enviados incorrectos.'
        ];

        if (!empty($params_array)) {
            // Validar datos
            $validate = Validator::make($params_array, [
                'title'     => 'required',
                'content'   => 'required',
                'category_id'     => 'required'
            ]);

            if ($validate->fails()) {
                $data['errors'] = $validate->errors();
                return response()->json($data, $data['code']);
            }

            // Quitar lo que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['category_id']);
            unset($params_array['user_id']);
            unset($params_array['user']);
            unset($params_array['created_at']);

            // Actualizar el artículo
            $post = Post::where('id', $id)->update($params_array);

            $data = [
                'code'       => 200,
                'status'     => 'success',
                'post'       => $params_array
            ];
        }


        // Devolver los datos
        return response()->json($data, $data['code']);
    }
}
