<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();

        return response()->json([
            'code'       => 200,
            'status'     => 'success',
            'categories' => $categories
        ]);
    }

    public function show($id) {
        $category = Category::find($id);

        if(is_object($category)) {
            $data = [
                'code'       => 200,
                'status'     => 'success',
                'category' => $category
            ];
        }else {
            $data = [
                'code'       => 404,
                'status'     => 'error',
                'message' => "La categoría no existe"
            ];
        }

        return response()->json($data, $data['code']);
    }
}
