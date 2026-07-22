from app.config import CACHE_DRIVER
from app.cache.memory_cache import MemoryCache
from app.cache.redis_cache import RedisCache
from app.gateways.open_meteo_gateway import OpenMeteoGateway
from app.services.weather_service import WeatherService


def get_weather_service():

    if CACHE_DRIVER == "redis":
        cache = RedisCache()
    else:
        cache = MemoryCache()

    return WeatherService(
        gateway=OpenMeteoGateway(),
        cache=cache
    )