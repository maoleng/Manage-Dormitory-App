<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mng\StorePostRequest;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {


        dd($request->validated());
    }
}
