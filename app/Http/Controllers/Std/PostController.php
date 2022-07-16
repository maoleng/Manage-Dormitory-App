<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class PostController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function index(Request $request): array
    {
        $category = $request->all();
        $posts = Post::query()
            ->where('category', $category)
            ->with('banner')
            ->get();
        $data = [];
        foreach ($posts as $key => $post) {
            $data[$key]['id'] = $post->id;
            $data[$key]['title'] = $post->title;
            $data[$key]['category'] = $post->categoryName;
            $data[$key]['banner'] = $post->banner->source;
            $data[$key]['created_at'] = $post->created_at->toDateTimeString();
        }

        return [
            'status' => true,
            'data' => $data,
        ];

    }
}

