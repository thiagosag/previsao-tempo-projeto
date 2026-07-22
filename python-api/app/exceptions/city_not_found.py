class CityNotFoundException(Exception):
    def __init__(self, city:str):
        super().__init__(
            f"Cidade '{city}' não encontrada."
        )