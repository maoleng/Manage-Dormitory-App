<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->all();
        $posts = Post::query()
            ->where('category', $category)
            ->with('banner')
            ->get();
//        dd($posts->toArray());
        $data = [];
        foreach ($posts as $key => $post) {
            $post[$key]['id'] = $post->id;
            $post[$key]['title'] = $post->title;
            $post[$key]['category'] = $post->categoryName;
            $post[$key]['banner'] = $post->banner->source;
            $post[$key]['created_at'] = $post->created_at;
        }
        dd($data);

    }
}
ErrorException: Indirect modification of overloaded element of App\Models\
Post has no effect in file E:\laragon\www\Manage-Dormitory-App\app\Http\Cont
rollers\Std\PostController.php on line 21
