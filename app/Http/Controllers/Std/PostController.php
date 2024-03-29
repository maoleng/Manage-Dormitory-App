<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class PostController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function index(Request $request): array
    {
        $query_params = $request->all();
        $category = $query_params['category'] ?? null;
        $related_post = $query_params['related'] ?? null;

        $posts = Post::query()
            ->with('banner')
            ->orderBy('created_at', 'DESC')
            ->get();
        if (isset($category) && empty($related_post)) {
            $posts = Post::query()
                ->where('category', $category)
                ->with('banner')
                ->orderBy('created_at', 'DESC')
                ->get();
        }
        if (isset($category, $related_post)) {
            $tag_ids = Post::query()->find($related_post)->tags->pluck('id')->toArray();
            $posts = Post::query()
                ->where('category', $category)
                ->whereHas('tags', function($q) use($tag_ids) {
                    $q->whereIn('id', $tag_ids);
                })
                ->with('banner')
                ->orderBy('created_at', 'DESC')
                ->limit(3)
                ->get();
        }

        $data = [];
        foreach ($posts as $key => $post) {
            $data[$key]['id'] = $post->id;
            $data[$key]['title'] = $post->title;
            $data[$key]['category'] = $post->categoryName;
            $data[$key]['banner'] = $post->banner->source ?? null;
            $data[$key]['created_at'] = $post->created_at;
        }

        return [
            'status' => true,
            'data' => $data,
        ];

    }

    public function newPost(Request $request): array
    {
        $category = $request->all()['category'];
        $check = in_array($category, ['2', '3', '4'], true);
        if ( !$check ) {
            return [
                'status' => false,
                'message' => 'Sai thể loại'
            ];
        }

        $posts = Post::query()
            ->where('category', $category)
            ->with('banner')
            ->orderBy('created_at', 'DESC')
            ->limit(3)->get();
        $btf_posts = $posts->map(static function($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'category' => $post->category,
                'banner' => $post->banner->source,
                'created_at' => $post->created_at,
            ];
        });

        return [
            'status' => true,
            'data' => $btf_posts,
        ];

    }

    public function show($id): array
    {
        $post = Post::query()->find($id);
        if (empty($post)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy bài đăng'
            ];
        }

        $tag_array = $post->tags;
        $tags = [];
        foreach ($tag_array as $tag) {
            $tags[] = [
                'id' => $tag->id,
                'name' => $tag->name,
                'color' => $tag->color,
            ];
        }

        return [
            'status' => true,
            'data' => [
                'post' => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'banner' => $post->banner->source ?? null,
                    'content' => $post->content,
                    'category' => $post->categoryName,
                    'created_at' => $post->created_at,
                ],
                'tags' => $tags,
            ]
        ];

    }
}

