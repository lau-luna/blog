<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //almacenamiento Eliminar y despues crear directorios
        Storage::deleteDirectory('users');
        Storage::deleteDirectory('posts');

        Storage::makeDirectory('users');
        Storage::makeDirectory('posts');

      
        // Factories
        User::factory(10)->create();
        Category::factory()->count(10)->create();
        // Post::factory(10)->create();
    }
}
