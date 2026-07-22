from typing import Protocol

class Cache(Protocol):
    def get(self, key: str):
        ...
    def set(
            self,
            key:str,
            value,
            ttl:int
    ):
        ...