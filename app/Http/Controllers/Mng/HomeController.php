<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use JetBrains\PhpStorm\ArrayShape;

class HomeController extends Controller
{
    #[ArrayShape(['status' => "bool", 'app_name' => "string"])]
    public function index(): array
    {
        return [
            'status' => true,
            'app_name' => 'Manage Dormitory App',
        ];
    }

}
