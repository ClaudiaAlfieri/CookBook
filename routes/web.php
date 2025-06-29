<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/comoFunciona', function () {
    return view('ComoFunciona');
});
Route::get('/minhasReceitas', function () {
    return view('MinhasReceitas');
});

