<?php

namespace App\Http\Controllers; // Onde fica essa classe -> dentro da pasta app/http/controllers

use App\Http\Requests\GetWeatherRequest; // Importa a validação isolada (Form Request)
use App\Services\WeatherApiService; // import do serviço da API 
use Exception;

class WeatherController extends Controller // nova classe WeatherController usa/herda as funcionalidades do Controller base do Laravel.

{
    // Injeção de Dependência pelo construtor (Boa prática do Laravel)
    public function __construct(
        protected WeatherApiService $service
    ) {}

    public function index(GetWeatherRequest $request)
    {
        // O Form Request garante que 'city' é uma string válida se fornecida
        $city = $request->input('city');

        if (!$city) {
            return view('weather', ['weather' => null, 'error' => null]);
        }

        try {
            $weatherData = $this->service->getWeather($city);

            // Se deu certo, passamos o clima e garantimos que o erro é nulo
            return view('weather', [
                'weather' => $weatherData,
                'error' => null
            ]);

        } catch (Exception $e) {
            // Se deu erro (como cidade não encontrada ou API fora do ar), passamos o erro e o clima como nulo
            return view('weather', [
                'weather' => null,
                'error' => $e->getMessage()
            ]);
        }
    }
}