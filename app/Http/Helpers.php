<?php


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
