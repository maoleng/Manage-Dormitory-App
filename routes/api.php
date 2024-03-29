<?php

use App\Http\Controllers\Mng\MistakeController;
use App\Http\Controllers\Std;
use App\Http\Controllers\Mng;
use App\Http\Middleware\AuthApp;
use App\Http\Middleware\GuardRole;
use App\Http\Middleware\AuthMng;
use App\Http\Middleware\ManagerRole;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'std'], static function() {
    Route::get('/', [Std\HomeController::class, 'index'])->name('index');
    Route::post('/login', [Std\AuthController::class, 'login']);

    Route::group(['prefix' => 'post'], static function() {
        Route::get('/', [Std\PostController::class, 'index']);
        Route::get('/new', [Std\PostController::class, 'newPost']);
        Route::get('/{id}', [Std\PostController::class, 'show']);

    });
});

Route::group(['prefix' => 'std', 'middleware' => AuthApp::class], static function() {
    Route::group(['prefix' => 'student'], static function() {
        Route::get('/', [Std\StudentController::class, 'index']);
        Route::get('/me', [Std\StudentController::class, 'me']);
    });
    Route::group(['prefix' => 'contract'], static function() {
        Route::get('/form', [Std\ContractController::class, 'form']);
        Route::post('/register', [Std\ContractController::class, 'register']);
        Route::get('/registration', [Std\ContractController::class, 'registration']);
    });
    Route::group(['prefix' => 'mistake'], static function() {
        Route::get('/', [Std\MistakeController::class, 'all']);
        Route::put('/{id}', [Std\MistakeController::class, 'confirm']);
    });
    Route::group(['prefix' => 'form'], static function() {
        Route::get('/', [Std\FormController::class, 'all']);
        Route::get('/{id}', [Std\FormController::class, 'showConversation']);
        Route::post('/', [Std\FormController::class, 'store']);
        Route::post('/answer', [Std\FormController::class, 'answer']);
    });

    Route::group(['middleware' => GuardRole::class], static function() {
        Route::group(['prefix' => 'schedule'], static function() {
            Route::get('/', [Std\ScheduleController::class, 'index']);
            Route::get('/temp1', [Std\ScheduleController::class, 'index1']);
            Route::get('/temp2', [Std\ScheduleController::class, 'index2']);
            Route::get('/check_empty_schedule', [Std\ScheduleController::class, 'checkIfEmptySchedule']);
            Route::post('/save', [Std\ScheduleController::class, 'save']);
        });
        Route::group(['prefix' => 'attendance'], static function() {
            Route::get('/get_rooms', [Std\AttendanceController::class, 'getRooms']);
            Route::get('/get_students/{id}', [Std\AttendanceController::class, 'getStudents']);
            Route::post('/check_attendance', [Std\AttendanceController::class, 'checkAttendance']);
        });

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
    Route::group(['prefix' => 'mistake'], static function() {
        Route::get('/', [Mng\MistakeController::class, 'list']);
        Route::get('/mistake_type', [Mng\MistakeController::class, 'mistakeType']);
        Route::get('/{id}', [Mng\MistakeController::class, 'show']);
        Route::post('/', [Mng\MistakeController::class, 'store']);
        Route::post('/{id}', [Mng\MistakeController::class, 'update']);
        Route::post('/fix_mistake/{id}', [Mng\MistakeController::class, 'fixMistake']);
    });
    Route::group(['prefix' => 'form'], static function() {
        Route::get('/', [Mng\FormController::class, 'all']);
        Route::get('/{id}', [Mng\FormController::class, 'showConversation']);
        Route::post('/answer', [Mng\FormController::class, 'answer']);
    });

    Route::group(['middleware' => ManagerRole::class], static function() {
        Route::group(['prefix' => 'contract'], static function() {
            Route::get('/', [Mng\ContractController::class, 'all']);
            Route::get('/forms', [Mng\ContractController::class, 'forms']);
            Route::get('/{id}', [Mng\ContractController::class, 'show']);
            Route::post('/form_deny/{id}', [Mng\ContractController::class, 'formDeny']);
            Route::post('/form_confirm/{id}', [Mng\ContractController::class, 'formConfirm']);
            Route::post('/pick_room/{id}', [Mng\ContractController::class, 'pickRoom']);
        });
        Route::group(['prefix' => 'building'], static function() {
            Route::get('/', [Mng\BuildingController::class, 'all']);
        });
        Route::group(['prefix' => 'floor'], static function() {
            Route::get('/', [Mng\FloorController::class, 'all']);
        });
        Route::group(['prefix' => 'room'], static function() {
            Route::get('/', [Mng\RoomController::class, 'all']);
        });
        Route::group(['prefix' => 'detail'], static function() {
            Route::get('/', [Mng\DetailController::class, 'index']);
        });
        Route::group(['prefix' => 'subscription'], static function() {
            Route::get('/{id}', [Mng\SubscriptionController::class, 'detail']);
            Route::put('/{id}', [Mng\SubscriptionController::class, 'update']);
            Route::post('/download', [Mng\SubscriptionController::class, 'downloadBill']);
            Route::get('/year_range/{type}', [Mng\SubscriptionController::class, 'getYearRange']);
        });
        Route::group(['prefix' => 'post'], static function() {
            Route::get('/', [Mng\PostController::class, 'index']);
            Route::post('/', [Mng\PostController::class, 'store']);
            Route::put('/{id}', [Mng\PostController::class, 'update']);
        });
        Route::group(['prefix' => 'tag'], static function() {
            Route::get('/', [Mng\TagController::class, 'index']);
            Route::post('/', [Mng\TagController::class, 'store']);
        });
        Route::group(['prefix' => 'electricity_water'], static function() {
            Route::get('/', [Mng\ElectricityWaterController::class, 'index']);
            Route::get('/{id}', [Mng\ElectricityWaterController::class, 'detail']);
        });
        Route::group(['prefix' => 'student'], static function() {
            Route::get('/', [Mng\StudentController::class, 'index']);
            Route::get('/{id}', [Mng\StudentController::class, 'detail']);
            Route::put('/{id}', [Mng\StudentController::class, 'update']);
        });
        Route::group(['prefix' => 'attendance'], static function() {
            Route::get('/', [Mng\AttendanceController::class, 'index']);
            Route::get('/{id}', [Mng\AttendanceController::class, 'detailStudent']);
        });
        Route::group(['prefix' => 'statistic'], static function() {
            Route::get('/mistake_reason', [Mng\StatisticController::class, 'mistakeReason']);
        });
    });


});

Route::get('/test', static function () {
    $a = Carbon::now();
    dd($a);
})->name('test');
Route::get('/test123', static function() {

})->name('test123');
