import requests # permite acessar sites e APIs (fazer requests)
from app.models.weather_response import WeatherResponse # modelo WeatherResponse

class WeatherGateway:
    GEO_URL = "https://geocoding-api.open-meteo.com/v1/search" # coordenadas
    WEATHER_URL = "https://api.open-meteo.com/v1/forecast" # buscar o clima 
    # link sobre a api: https://open-meteo.com/en/docs

    def get_weather(self, city:str) -> WeatherResponse:
        geo = requests.get(
            self.GEO_URL, # self - encontra a var global GEO_URL
            params={
                "name": city,
                "count": 1 # devolve o primeiro resultado
            }
        )

        geo.raise_for_status() # método para verificar status da api geo

        place = geo.json()["results"][0] # guarda o resultado 1, uma array na var place

        weather = requests.get( # busca o clima a partir das coordenadas
            self.WEATHER_URL,
            params={
                "latitude": place["latitude"],  # place - dados 'fixos' a partir da cidade
                "longitude": place["longitude"],
                "current": "temperature_2m,relative_humidity_2m,wind_speed_10m" # current - dados atuais
            }
        )

        weather.raise_for_status()

        current = weather.json()["current"]

        return WeatherResponse(
            city=place["name"],
            country=place["country"],
            temperature=current["temperature_2m"],
            humidity=current["relative_humidity_2m"],
            wind=current["wind_speed_10m"]
        )