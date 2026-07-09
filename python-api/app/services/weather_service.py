import requests # permite acessar sites e APIs (fazer requests)

from app.cache.memory_cache import MemoryCache
from app.config import CACHE_TTL

class WeatherService:
    GEO_URL = "https://geocoding-api.open-meteo.com/v1/search" # coordenadas
    WEATHER_URL = "https://api.open-meteo.com/v1/forecast" # buscar o clima

    cache = MemoryCache()

    def get_weather(self, city: str): # permite usar as funções internas da classe/ espera receber

        key = city.lower()

        cached = self.cache.get(key)

        if cached:
            print("Resposta veio do CACHE :)")
            return cached
        
        print("Resposta veio da API :o")

        geo = requests.get(
            self.GEO_URL, # permite com que o programa encontre var globais
            params={
                "name": city,
                "count": 1 # devolve apenas 1 resultado (a cidade digitada)
            }
        )

        geo.raise_for_status() # verificar status

        data = geo.json() # resposta 

        if "results" not in data:
            raise Exception("Cidade não encontrada.") # o raise para o código quando não encontra o resultado em data
        
        place = data["results"][0] # guarda o resultado 1, isso é uma array

        latitude = place["latitude"]
        longitude = place["longitude"]

        weather = requests.get(
            self.WEATHER_URL, # var global
            params={
                "latitude": latitude,
                "longitude": longitude,
                "current": "temperature_2m,relative_humidity_2m,wind_speed_10m" # dados atuais
            }
        )

        weather.raise_for_status()

        current = weather.json()["current"]

        return {
            "city": place["name"],
            "country": place["country"],
            "temperature": current["temperature_2m"],
            "humidity": current["relative_humidity_2m"],
            "wind": current["wind_speed_10m"]
        }