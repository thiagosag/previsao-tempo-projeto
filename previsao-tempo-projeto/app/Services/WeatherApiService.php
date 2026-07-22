<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Exception;

class WeatherApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.python_api.url', 'http://127.0.0.1:8001');
    }

    public function getWeather(string $city): array
    {
        try {
            // Requisição para a API Python
            $response = Http::timeout(3)
                ->get("{$this->baseUrl}/weather", [
                    'city' => $city,
                ]);

            // Se o Python retornar erro (404, 500, etc)
            if ($response->failed()) {
                $errorData = $response->json();
                
                // Pega a mensagem de erro que veio do Python (detail ou message)
                $message = $errorData['detail'] ?? $errorData['message'] ?? null;

                if (!$message) {
                    if ($response->status() === 404) {
                        $message = "Cidade '{$city}' não foi encontrada.";
                    } else {
                        $message = "Não foi possível obter a previsão para '{$city}'. Verifique o nome digitado.";
                    }
                }

                throw new Exception($message);
            }

            return $response->json();

        } catch (ConnectionException $e) {
            throw new Exception("O serviço de clima está temporariamente indisponível.");
        }
    }
}