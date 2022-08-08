<?php


use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\App;

if (function_exists('c')) {
    throw new Exception('function "c" is already existed !');
} else {
    function c(string $key)
    {
        return App::make($key);
    }
}

if (!function_exists('size')) {
    function size($string)
    {
        return round((int)(strlen(rtrim($string, '=')) * 0.75) / 1024, 2);
    }
}

if (!function_exists('checkSpam')) {
    function checkSpam($class): bool
    {
        $check = $class::query()->where('date', Carbon::now())->first();
        if ($check) {
            return true;
        }
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Đừng spam bạn ơi',
        ]));
    }
}
