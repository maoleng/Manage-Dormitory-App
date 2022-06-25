<?php

use App\Http\Controllers\App;
use App\Http\Controllers\Mng;
use App\Http\Middleware\AuthApp;
use App\Http\Middleware\AuthMng;
use App\Http\Middleware\ManagerRole;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], static function() {
    Route::get('/', [App\HomeController::class, 'index']);
    Route::post('/login', [App\AuthController::class, 'login']);

});

Route::group(['prefix' => 'app', 'middleware' => AuthApp::class], static function() {
    Route::group(['prefix' => 'student'], static function() {
        Route::get('/', [App\StudentController::class, 'index']);
        Route::get('/me', [App\StudentController::class, 'me']);
    });
    Route::group(['prefix' => 'contract'], static function() {
        Route::get('/form', [App\ContractController::class, 'form']);
        Route::post('/register', [App\ContractController::class, 'register']);
        Route::get('/registration', [App\ContractController::class, 'registration']);
    });

});

Route::group(['prefix' => 'mng'], static function() {
    Route::get('/', [Mng\HomeController::class, 'index']);
    Route::post('/login', [Mng\AuthController::class, 'login']);

});

Route::group(['prefix' => 'mng', 'middleware' => AuthMng::class], static function() {
    Route::group(['prefix' => 'teacher'], static function() {
        Route::get('/', [Mng\TeacherController::class, 'index']);
        Route::get('/me', [Mng\TeacherController::class, 'me']);
    });

    Route::group(['middleware' => ManagerRole::class], static function() {
        Route::group(['prefix' => 'contract'], static function() {
            Route::get('/', [Mng\ContractController::class, 'all']);
            Route::get('/forms', [Mng\ContractController::class, 'forms']);
            Route::post('/form_confirm/{id}', [Mng\ContractController::class, 'formConfirm']);
            Route::post('/pick_room/{id}', [Mng\ContractController::class, 'pickRoom']);
        });
        Route::group(['prefix' => 'room'], static function() {
            Route::get('/', [Mng\RoomController::class, 'all']);
        });
        Route::group(['prefix' => 'subscription'], static function() {
            Route::put('/{id}', [Mng\SubscriptionController::class, 'update']);
        });
    });


});

