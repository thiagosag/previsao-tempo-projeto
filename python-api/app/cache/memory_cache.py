from time import time # biblioteca nativa - horário exato

from app.cache.contracts.cache import Cache

class MemoryCache(Cache):
    def __init__(self): # construtor da classe. 
        # Dunder (Double Underline) indica que será executado automaticamente toda vez que a classe MemoryCache é chamada
        # inicializa/cria o objeto
        self.storage = {} # salva na memória RAM do próprio server
    def get(self, key): # recebe a chave de busca (key -> cidade)

        item = self.storage.get(key) # Busca a chave no cache

        if not item:
            return None # Se não tiver, interrompa e retorne nada
        
        if item["expires"] < time(): # se o item expirou (passou de 600 segundos)

            del self.storage[key] # ele é deletado do cache
            return None # retorna nada
        
        return item["value"] # se não expirou, retorna os valores
    
    def set(self, key, value, ttl=600): # define a função set, que guarda as informações; time to live => 600 segundos 
        self.storage[key] = {
            "value": value,
            "expires": time() + ttl
        }