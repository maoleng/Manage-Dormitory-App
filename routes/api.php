<?php

use App\Http\Controllers\App\AuthController;
use App\Http\Controllers\App\ContractController;
use App\Http\Controllers\App\HomeController;
use App\Http\Controllers\App\StudentController;
use App\Http\Middleware\AuthApp;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], static function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::post('/login', [AuthController::class, 'login']);

});

Route::group(['prefix' => 'app', 'middleware' => AuthApp::class], static function() {
    Route::group(['prefix' => 'student'], static function() {
        Route::get('/', [StudentController::class, 'index']);
        Route::get('/me', [StudentController::class, 'me']);
    });

    Route::group(['prefix' => 'contract'], static function() {
        Route::get('/form', [ContractController::class, 'form']);
        Route::post('/register', [ContractController::class, 'register']);
    });

});
