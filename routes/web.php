<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/comoFunciona', function () {
    return view('ComoFunciona');
});
Route::get('/minhasReceitas', function () {
    return view('MinhasReceitas');
});
Route::get('/contato', function () {
    return view('Contato');
});

Route::post('/generate-recipe', [RecipeController::class, 'generateRecipe']);
