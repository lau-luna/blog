<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'code'       => 200,
            'status'     => 'success',
            'categories' => $categories
        ]);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (is_object($category)) {
            $data = [
                'code'       => 200,
                'status'     => 'success',
                'category' => $category
            ];
        } else {
            $data = [
                'code'       => 404,
                'status'     => 'error',
                'message' => "La categoria no existe"
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {
        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if ($params_array) {
            // Validar los datos
            $validate = Validator::make($params_array, [
                'name'      => 'required|alpha'
            ]);

            // Guardar la categoría 
            if ($validate->fails()) {
                $data = [
                    'code'       => 400,
                    'status'     => 'error',
                    'message'    => 'No se ha guardado la categoria.'
                ];
            } else {
                $category = new Category();
                $category->name = $params_array['name'];
                $category->save();

                $data = [
                    'code'       => 200,
                    'status'     => 'success',
                    'category'   => $category
                ];
            }
        } else {
            $data = [
                'code'       => 400,
                'status'     => 'error',
                'message'    => 'No has enviado ninguna categoría.'
            ];
        }

        // Devolver el resultado
        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request)
    {
        // Recoger datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);


        if (!empty($params_array)) {
            // Validar los datos
            $validate = Validator::make($params_array, [
                'name'      => 'required|alpha'
            ]);

            // Quitar lo que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['created_at']);

            // Actualizar la categoría
            $category = Category::where('id', $id)->update($params_array);

            $data = [
                'code'       => 200,
                'status'     => 'success',
                'category'   => $params_array
            ];

        } else {
            $data = [
                'code'       => 400,
                'status'     => 'error',
                'message'    => 'No has enviado ninguna categoría.'
            ];
        }

        // Devolver los datos
        return response()->json($data, $data['code']);
    }
}
