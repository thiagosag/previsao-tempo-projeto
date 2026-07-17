<?php

namespace App\Http\Controllers; # Onde fica essa classe -> dentro da pasta app/http/controllers

use App\Services\WeatherApiService; # import do serviço da API 

use Illuminate\Http\Request; # classe Request nativa do Laravel
# lida com os dados enviados pelo usuário (como parâmetros de URL, formulários, etc.).

class WeatherController extends Controller # nova classe WeatherController usa/herda as funcionalidades do Controller base do Laravel.
{
    public function index(Request $request, WeatherApiService $service)
{
    $city = $request->input('city');

    if (!$city) {
        return view('weather', ['weather' => null, 'error' => null]);
    }

    try {
        $weatherData = $service->getWeather($city);

        // Se deu certo, passamos o clima e garantimos que o erro é nulo
        return view('weather', [
            'weather' => $weatherData,
            'error' => null
        ]);

    } catch (\Exception $e) {
        // Se deu erro (como cidade não encontrada), passamos o erro e o clima como nulo
        return view('weather', [
            'weather' => null,
            'error' => $e->getMessage()
        ]);
    }
}
}
