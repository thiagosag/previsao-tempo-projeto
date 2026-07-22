import json
import redis
from app.models.weather_response import WeatherResponse


class RedisCache:

    def __init__(self):
        self.redis = redis.Redis(
            host="127.0.0.1",
            port=6379,
            decode_responses=True,
            socket_connect_timeout=2,
        )

    def get(self, key: str):
        try:
            value = self.redis.get(key)
            if not value:
                return None

            data = json.loads(value)
            return WeatherResponse(**data)
        except (redis.ConnectionError, redis.TimeoutError):
            # Se o Redis estiver fora do ar ou demorar,
            # finge que não tem cache e deixa o app buscar na Open-Meteo sem travar
            return None

    def set(self, key: str, value, ttl: int):
        try:
            self.redis.setex(
                key,
                ttl,
                value.model_dump_json()
            )
        except (redis.ConnectionError, redis.TimeoutError):
            # Se não conseguir salvar no Redis, silencia o erro
            pass