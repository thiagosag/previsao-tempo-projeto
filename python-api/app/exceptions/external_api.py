class ExternalApiException(Exception):
    def __init__(self):
        super().__init__(
            "Erro ao consultar o serviço meteorológico"
        )