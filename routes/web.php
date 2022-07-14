<?php

use App\Http\Controllers\Std\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return [
        'status' => true,
        'message' => 'Sai URL kìa má',
        'url' => route('index')
    ];
});

Route::get('/test', function() {
    $schedules = (new ScheduleController)->index();
    return $schedules;
//    return view('test', [
//        'schedules' => $schedules['data']
//    ]);
});
