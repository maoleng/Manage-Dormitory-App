<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mng\StorePostRequest;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class PostController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function index(): array
    {
        $posts = Post::query()
            ->whereIn('category', [2, 3, 4])
            ->with('teacher')
            ->with('banner')
            ->with('tags')
            ->get();
        $data = [];
        foreach ($posts as $key => $post) {
            $data[$key]['id'] = $post->id;
            $data[$key]['title'] = $post->title;
            $data[$key]['category'] = $post->categoryName;
            $data[$key]['banner'] = $post->banner->source;
            $data[$key]['teacher_name'] = $post->teacher->name;
            $tags = $post->tags;
            foreach ($tags as $key2 => $tag) {
                $data[$key]['tags'][$key2]['name'] = $tag->name;
                $data[$key]['tags'][$key2]['color'] = $tag->color;
            }
            $data[$key]['created_at'] = $post->created_at;
            $data[$key]['updated_at'] = $post->updated_at;
        }

        return [
            'status' => true,
            'data' => $data
        ];

    }


    #[ArrayShape(['status' => "bool", 'data' => "\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model"])]
    public function store(StorePostRequest $request): array
    {
        $data = $request->validated();
        $banner = Image::query()->create([
            'source' => $data['banner'],
            'size' => size($data['banner'])
        ]);
        $post = Post::query()->create([
            'title' => $data['title'],
            'content' => $data['content'],
            'category' => $data['category'],
            'banner_id' => $banner->id,
            'teacher_id' => c('teacher')->id,
        ]);
        $post->tags()->sync($data['tag_ids']);

        return [
            'status' => true,
            'data' => $post
        ];
    }


}
