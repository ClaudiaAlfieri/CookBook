<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\TestGeminiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sobre', function () {
    return view('about');
});

// Rota para gerar receita
Route::post('/generate-recipe', [RecipeController::class, 'generateRecipe']);
