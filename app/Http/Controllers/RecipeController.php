<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecipeController extends Controller
{
    public function generateRecipe(Request $request)
    {
        // Validar entrada
        $ingredients = $request->input('ingredients', []);

        // Se vier como string, converte para array
        if (is_string($ingredients)) {
            $ingredients = explode(',', $ingredients);
        }

        // Limpar ingredientes
        $ingredients = array_filter(array_map('trim', $ingredients));

        if (empty($ingredients)) {
            return response()->json([
                'success' => false,
                'error' => 'Nenhum ingrediente fornecido'
            ], 400);
        }

        // Limitar número de ingredientes
        if (count($ingredients) > 15) {
            return response()->json([
                'success' => false,
                'error' => 'Máximo de 15 ingredientes permitido'
            ], 400);
        }

        // Criar prompt melhorado
        $prompt = $this->createPrompt($ingredients);

        try {
            $response = $this->callGemini($prompt);

            return response()->json([
                'success' => true,
                'recipe' => $response,
                'ingredients_used' => $ingredients
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao gerar receita', [
                'error' => $e->getMessage(),
                'ingredients' => $ingredients
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erro ao gerar receita. Tente novamente.'
            ], 500);
        }
    }

    private function createPrompt($ingredients)
    {
        $ingredientsList = implode(', ', $ingredients);

        return "Você é um chef experiente. Com base nestes ingredientes: {$ingredientsList}

Crie uma receita deliciosa em português de portugal. Estruture assim:

**NOME DA RECEITA:**
[Nome atrativo]

**INGREDIENTES:**
[Lista com quantidades]

**TEMPO DE PREPARO:**
[Tempo estimado]

**RENDIMENTO:**
[Porções]

**MODO DE PREPARO:**
1. [Primeiro passo]
2. [Segundo passo]
[Continue numerando...]

**DICAS:**
[Dicas úteis]

Seja criativo mas prático!";
    }

    private function callGemini($prompt)
    {
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            throw new \Exception('API Key não configurada no .env');
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$apiKey}";

        $response = Http::timeout(30)->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 1000,
            ]
        ]);

        if ($response->failed()) {
            $status = $response->status();
            Log::error('Erro na API Gemini', [
                'status' => $status,
                'response' => $response->body()
            ]);

            throw new \Exception("Erro na API Gemini (Status: {$status})");
        }

        $data = $response->json();

        if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            Log::error('Resposta inválida da API', ['response' => $data]);
            throw new \Exception('Resposta inválida da API');
        }

        return $data['candidates'][0]['content']['parts'][0]['text'];
    }

}
