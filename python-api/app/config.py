from os import getenv

CACHE_DRIVER = getenv("CACHE_DRIVER", "memory")

CACHE_TTL = 600 
# define que qualquer dado guardado no cache do seu sistema será válido por 600 segundos (10 minutos).