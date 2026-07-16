from app.models.weather_response import WeatherResponse # modelo WeatherResponse

from app.cache.memory_cache import MemoryCache 

from app.config import CACHE_TTL # varíavel que guarda os 600 segundos em config.py

from app.gateways.weather_gateway import WeatherGateway

class WeatherService:
    
    def __init__(self, gateway):
        self.geteway = WeatherGateway()
        self.cache = MemoryCache()

    def get_weather(self, city) -> WeatherResponse: # self - permite usar as funções internas da classe/ espera receber
        # -> WeatherResponse: a seguir serão utilizadas os tipos declarados em WeatherResponse.

        key = city.lower().strip() # chave digitada -> cidade transformada em minúsculo e sem espaços extras (início e fim)

        cached = self.cache.get(key) # busca no cache se essa requisição foi feita nos últimos 10 min
        if cached:
            return cached


        weather = self.geteway.get_weather(city)

        self.cache.set(
            key, weather, CACHE_TTL
        )

        return weather
