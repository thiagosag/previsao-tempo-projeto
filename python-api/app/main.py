from fastapi import FastAPI, Request, Depends # fastAPI -> cria a API

from fastapi.responses import JSONResponse # resposta http no formato json

from .services.weather_service import WeatherService # importa dentro da pasta services o weather_service.py

# from app.models.weather_response import WeatherResponse # Formato de Dados (DTO - Data Transfer Object)
# from .gateways.open_meteo_gateway import OpenMeteoGateway  # comunicação externa

from app.exceptions.city_not_found import CityNotFoundException # tratamento de erros
from app.exceptions.external_api import ExternalApiException

from app.providers.weather_provider import get_weather_service


import logging # Importa a biblioteca nativa de logs do Python

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(message)s"
)

logger = logging.getLogger(__name__)

app = FastAPI() # cria a instância app (que mostra pro server quais rotas existem)
# instância é um objeto criado a partir de uma classe (molde de atributos e métodos)

@app.exception_handler(
     CityNotFoundException
)
async def city_not_found_handler(
    request: Request,
    exc: CityNotFoundException
):
    logger.warning(f"Busca de cidade falhou: {str(exc)}") # REGISTRA UM AVISO NO LOG: Útil para monitorar quais cidades as pessoas buscam e não existem
    return JSONResponse(
        status_code=404,
        content={
            "code": "CITY_NOT_FOUND",
            "message": str(exc)
        }
    )

@app.exception_handler(
    ExternalApiException
)

async def external_api_handler(
    request: Request,
    exc: ExternalApiException
):
    logger.error(f"Erro ao consultar o provedor Open-Meteo externo: {str(exc)}") # REGISTRA UM ERRO GRAVE NO LOG: Alerta o administrador do sistema que o Open-Meteo caiu ou falhou
    return JSONResponse(
        status_code=502,
        content={
            "code": "WEATHER_PROVIDER_ERROR",
            "message": str(exc)
        }
    )

@app.get( # @ -> decorador -> use a função abaixo a partir das funcionalidades de app 
        "/weather") 

# Como seria sem o @ - teria que registrar a rota manualmente depois:
# def weather(city: str):
#    return service.get_weather(city)
# app.add_api_route("/weather", weather, methods=["GET"])

def weather(
    city: str,
    service: WeatherService = Depends(
        get_weather_service
    ) 
):
    return service.get_weather(city)