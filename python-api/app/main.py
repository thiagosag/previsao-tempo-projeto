from fastapi import FastAPI, HTTPException # fastAPI -> cria a API; HTTPException -> retorna os erros HTTP de forma limpa
from .services.weather_service import WeatherService # importa dentro da pasta services o weather_service.py

from app.models.weather_response import WeatherResponse

from .gateways.weather_gateway import WeatherGateway

app = FastAPI() # cria a instância app (que mostra pro server quais rotas existem)
# instância é um objeto criado a partir de uma classe (molde de atributos e métodos)

gateway = WeatherGateway()
service = WeatherService(gateway=gateway)


@app.get( # @ -> decorador -> use a função abaixo a partir das funcionalidades de app 
        "/weather",
        response_model=WeatherResponse
        ) 

# Como seria sem o @ - teria que registrar a rota manualmente depois:
# def weather(city: str):
#    return service.get_weather(city)
# app.add_api_route("/weather", weather, methods=["GET"])

def weather(city: str) -> WeatherResponse: 
    # define a função weather (que exige a str city)
    # "->" Type Hinting (Indicação de Tipo) de retorno 
    try:
        return service.get_weather(city) # tente retornar algo da api a partir do serviço get_weather que vem da função WeatherService
    
    except Exception as e: # se não for possível, levantar a exceção, guardá-la como "e"
        raise HTTPException(
            status_code=404,
            detail=str(e) # erro 404 - detail: erro guardado em "e" (ex: Not Found)
        )
