<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return [
            'status' => true,
            'app_name' => 'Manage Dormitory App'
        ];
    }

}
