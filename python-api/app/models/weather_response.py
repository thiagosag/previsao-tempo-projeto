from pydantic import BaseModel # biblioteca: pydantic; classe: BaseModel

# validação de dados (um Schema) - cada var deve ser do tipo definido aqui.

# usado pela FastAPI  para validar, documentar e converter com facilidade

class WeatherResponse(BaseModel):
    city: str
    country: str
    temperature: float
    humidity: int
    wind: float