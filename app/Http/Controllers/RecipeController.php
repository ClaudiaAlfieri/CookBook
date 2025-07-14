<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;

class RecipeController extends Controller
{
    public function generateRecipe(Request $request)
    {
        $ingredients = $request->input('ingredients');

        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $prompt = "Com base nos seguintes ingredientes: " . implode(', ', $ingredients) .
                  "\n\nCrie uma receita deliciosa e detalhada em português. Inclua:\n" .
                  "- Nome da receita\n" .
                  "- Ingredientes necessários (use os fornecidos como base)\n" .
                  "- Modo de preparo passo a passo\n" .
                  "- Tempo de preparo\n" .
                  "- Rendimento\n" .
                  "- Dicas extras";

        try {
            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'Você é um chef especialista em criar receitas criativas e saborosas.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7,
            ]);

            return response()->json([
                'success' => true,
                'recipe' => $response->choices[0]->message->content
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao gerar receita: ' . $e->getMessage()
            ], 500);
        }
    }
}
