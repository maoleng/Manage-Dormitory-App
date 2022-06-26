<?php

use App\Models\Teacher;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', static function () {
    return [
        'status' => true,
        'message' => 'Sai URL kìa má',
        'url' => route('index')
    ];
});
