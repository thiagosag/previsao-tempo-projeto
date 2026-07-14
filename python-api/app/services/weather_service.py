import requests # permite acessar sites e APIs (fazer requests)

from app.models.weather_response import WeatherResponse # modelo WeatherResponse
from app.cache.memory_cache import MemoryCache 
from app.config import CACHE_TTL # varíavel que guarda os 600 segundos em config.py

class WeatherService:
    GEO_URL = "https://geocoding-api.open-meteo.com/v1/search" # coordenadas
    WEATHER_URL = "https://api.open-meteo.com/v1/forecast" # buscar o clima 
    # link sobre a api: https://open-meteo.com/en/docs

    cache = MemoryCache()

    def get_weather(self, city) -> WeatherResponse: # self - permite usar as funções internas da classe/ espera receber
        # -> WeatherResponse: a seguir serão utilizadas os tipos declarados em WeatherResponse.

        key = city.lower().strip() # chave digitada -> cidade transformada em minúsculo e sem espaços extras (início e fim)

        cached = self.cache.get(key) # busca no cache se essa requisição foi feita nos últimos 10 min
        if cached:
            return cached
        """        
        if cached:
            print("Resposta veio do CACHE")
            return cached
        
        print("Resposta veio da API")
        """

        # se não havia nada no cache: 1. Busca as coordenadas da cidade
        geo = requests.get(
            self.GEO_URL, # permite com que o programa encontre var globais
            params={
                "name": city,
                "count": 1 # devolve apenas 1 resultado (a cidade digitada)
            }
        )

        geo.raise_for_status() # método para verificar status da api geo

        data = geo.json() # resposta 

        if "results" not in data:
            raise Exception("Cidade não encontrada.") # o raise para o código quando não encontra o resultado em data
        
        place = data["results"][0] # guarda o resultado 1, isso é uma array na var place

        latitude = place["latitude"]
        longitude = place["longitude"]

        # 2. Busca o clima usando as coordenadas 
        weather = requests.get(
            self.WEATHER_URL, # var global
            params={
                "latitude": latitude,
                "longitude": longitude,
                "current": "temperature_2m,relative_humidity_2m,wind_speed_10m" # dados atuais
            }
        )

        weather.raise_for_status() # status da api weather

        current = weather.json()["current"] # resposta -> dicionário; extrai apenas a parte "current" que contém o clima atual.

        result = WeatherResponse(
            city=place["name"],
            country=place["country"],
            temperature=current["temperature_2m"],
            humidity=current["relative_humidity_2m"],
            wind=current["wind_speed_10m"]
        )

        """ 
        VERSÃO SEM O MODEL - weather_response
                result = {
                    "city": place["name"], # traz o resultado obtido na var place, o nome da cidade é salvo em city, no dicionário result
                    "country": place["country"],
                    "temperature": current["temperature_2m"], # traz o resultado atual de temperatura, humidade e vento
                    "humidity": current["relative_humidity_2m"],
                    "wind": current["wind_speed_10m"]
                }
        """  

        self.cache.set( # define/salva na memória, no cache para futuras requisições
            key, # nome da cidade
            result, # resultado obtido pela api
            CACHE_TTL # expira em 600 segundos
        )

        return result # retorna o resultado, as informações requisitadas