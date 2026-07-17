<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;  # Importa o HTTP Client do Laravel

class WeatherApiService # essa classe é chamada pelo WeatherController
{
    public function getWeather(string $city): array # método público getWeather; retorna um array
    { 
        // Requisição, guarda a resposta em uma variável
        $response = Http::get(
            'http://127.0.0.1:8001/weather', 
            [
                'city' => $city 
            ]
        );

        // Se a requisição falhar (Status 400, 404, 500, 502, etc.)
        if ($response->failed()) {
            $errorData = $response->json();
            
            // Mensagem amigável do FastAPI (se houver)
            $message = $errorData['message'] ?? 'Erro desconhecido ao buscar dados do clima.';
            
            // exceção a ser captada pelo try/catch do Controller
            throw new \Exception($message);
        }

        // Se ok, o array do clima transformado em JSON é retornado
        return $response->json(); 
    }
}