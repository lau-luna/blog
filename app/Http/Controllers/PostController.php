<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;

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
            $user = $this->getIdentity($request);

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

            // Conseguir usuario identificado
            $user = $this->getIdentity($request);

            // Conseguir el registro a actualizar
            $post = Post::where('id', $id)
                ->where('user_id', $user->sub)
                ->first();

            if (!empty($post) && is_object($post)){
                // Actualizar el registro
                $post->update($params_array);

                $data = [
                    'code'       => 200,
                    'status'     => 'success',
                    'post'       =>  $post,
                    'changes'       => $params_array
                ];
            }

            /*
            $where = [
                'id' => $id,
                'user_id' => $user->sub
            ];
            */


        }


        // Devolver los datos
        return response()->json($data, $data['code']);
    }

    public function destroy($id, Request $request)
    {
        // Conseguir usuario identificado
        $user = $this->getIdentity($request);

        // Conseguir el registro
        $post = Post::where('id', $id)
                    ->where('user_id', $user->sub)
                    ->first();

        if (!empty($post)) {
            // Borrarlo
            $post->delete();

            // Devolver algo
            $data = [
                'code'       => 200,
                'status'     => 'success',
                'post'       =>  $post
            ];
        } else {
            $data = [
                'code'       => 400,
                'status'     => 'error',
                'message'       =>  'El post no existe.'
            ];
        }

        return response()->json($data, $data['code']);
    }

    private function getIdentity(Request $request)
    {
        // Conseguir usuario identificado
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }

    public function upload(Request $request){
        // Recoger la imagen de la petición
        $image = $request->file('file0');

        // Validar la imagen
        $validate = Validator::make($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        // Guardar la imagen
        if (!$image ||  $validate->fails()) {
            $data = [
                'code'      => 400,
                'status'    => 'error',
                'message'   => 'Error al subir la imagen.'
            ];
        }else{
            $image_name = time().$image->getClientOriginalName();
            Storage::disk('images')->put($image_name, File::get($image));

            $data = array(
                'code'   => 200,
                'status' => 'success',
                'image'  => $image_name
            );
        }

        // Devolver datos
        return response()->json($data, $data['code']);
    }
}
