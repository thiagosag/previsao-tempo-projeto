# Recebe o gateway pelo construtor e gerencia o cache
from app.models.weather_response import WeatherResponse # modelo WeatherResponse

from app.cache.memory_cache import MemoryCache 

from app.config import CACHE_TTL # varíavel que guarda os 600 segundos em config.py

from app.gateways.open_meteo_gateway import WeatherGateway


class WeatherService: # algo genérico que guarda atributos e funções 
    
    def __init__(self, gateway, cache):
        self.gateway = gateway
        self.cache = cache

    def get_weather(self, city) -> WeatherResponse: # self - permite usar as funções da classe/ espera receber
        # -> WeatherResponse: a seguir serão utilizadas os tipos declarados em WeatherResponse.

        key = city.lower().strip() # chave digitada -> cidade transformada em minúsculo e sem espaços extras (início e fim)

        cached = self.cache.get(key) # busca no cache se essa requisição foi feita nos últimos 10 min
        if cached:
            return cached


        weather = self.gateway.get_weather(city)

        self.cache.set(
            key, weather, CACHE_TTL
        )

        return weather
