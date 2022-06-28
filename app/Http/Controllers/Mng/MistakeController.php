<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mng\StoreMistakeRequest;
use Illuminate\Http\Request;

class MistakeController extends Controller
{
    public function storeMistake(StoreMistakeRequest $request)
    {
        public function test(Request $request)
    {

        $path = $request->file('file')->path();
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        dd($base64);
    }
        dd($request->validated());
    }
}
