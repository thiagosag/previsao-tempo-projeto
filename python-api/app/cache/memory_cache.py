from time import time # biblioteca nativa - horário exato

class MemoryCache:
    def __init__(self): # inicializa/cria um 
        self.storage = {}
    def get(self, key):

        item = self.storage.get(key)

        if not item:
            return None
        
        if item["expires"] < time():

            del self.storage[key]
            return None
        return item["value"]
    def set(self, key, value, ttl=600): # time to live => 600 segundos 
        self.storage[key] = {
            "value": value,
            "expires": time() + ttl
        }