<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mng\StoreTagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class TagController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "mixed"])]
    public function index(): array
    {
        return [
            'status' => true,
            'data' => Tag::all()
        ];
    }

    #[ArrayShape(['status' => "bool", 'data' => "\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model"])]
    public function store(StoreTagRequest $request): array
    {
        $data = $request->validated();
        $create = Tag::query()->create($data);
        return [
            'status' => true,
            'data' => $create
        ];
    }
}
