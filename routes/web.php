<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\TestGeminiController;

Route::get('/', function () {
    return view('index');
});

// Rota para gerar receita:
Route::post('/generate-recipe', [RecipeController::class, 'generateRecipe']);
