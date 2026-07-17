<h1> Previsão do Tempo </h1> <!-- Exibição !-->

<form>
    <input name="city" 
    placeholder="Digite uma cidade"> <!-- parâmetro city (cidade enviada) !-->

    <button>
        Buscar
    </button>

</form>

<hr>

@if(isset($error) && $error)
    <div style="color: red; background: #fee2e2; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        {{ $error }}
    </div>
@endif

@if(isset($weather) && $weather)
    <h2>{{ $weather['city'] }}</h2>
    <p>País: {{ $weather['country'] }}</p>
    <p>Temperatura: {{ $weather['temperature'] }} ºC</p>
    <p>Umidade: {{ $weather['humidity'] }} %</p>
    <p>Vento: {{ $weather['wind'] }} km/h</p>
@endif