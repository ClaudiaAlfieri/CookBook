<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\TestGeminiController;

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

// Rota para gerar receita
Route::post('/generate-recipe', [RecipeController::class, 'generateRecipe']);
