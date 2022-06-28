<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class HomeController extends Controller
{
    #[ArrayShape(['status' => "bool", 'app_name' => "string"])]
    public function index(): array
    {
        return [
            'status' => true,
            'app_name' => 'Manage Dormitory App'
        ];
    }

}
