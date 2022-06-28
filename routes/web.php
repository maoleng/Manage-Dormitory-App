<?php

use App\Http\Controllers\Std\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return [
        'status' => true,
        'message' => 'Sai URL kìa má',
        'url' => route('index')
    ];
});

Route::post('/test', [HomeController::class, 'test']);
