<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
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

    
}
