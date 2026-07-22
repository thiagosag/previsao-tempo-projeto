# Pipeline de Previsão do Tempo

Projeto de estudos para aprender conceitos de Engenharia de Dados utilizando Python para coleta e processamento de dados e Laravel para disponibilização das informações por meio de uma API e interface web.

### Objetivos Gerais

- Construir um microsserviço resiliente em Python para integração meteorológica.
- Implementar padrões de Cache Local e Cache Distribuído (Redis).
- Expor e consumir dados através de REST APIs integradas.
- Praticar conceitos avançados de SOLID, Injeção de Dependência e DTOs.
- Aplicar princípios de resiliência e tratamento de exceções entre sistemas (PHP + Python).

### Objetivos de Aprendizagem

- Consumo de APIs REST com Gateways e DTOs (Pydantic)
- Arquitetura de Cache Distribuído com Redis e Docker
- Inversão de Dependência (SOLID) e Contratos (`Protocol`)
- Resiliência na comunicação HTTP (Timeouts e Retries com Guzzle)
- Tratamento global de exceções e logs estruturados
- Testes unitários com Fakes e Mocks (Pytest)

## Tecnologias

### Python (Backend / API Meteorológica)

- **Python 3.10+**
- **FastAPI** (Framework web de altíssima performance)
- **Uvicorn** (Servidor ASGI)
- **Pydantic** (Validação e tipagem de dados / DTOs)
- **Redis** (Driver de comunicação com o servidor Redis)
- **Requests** (Consumo da API Open-Meteo)
- **Pytest** (Suíte de testes unitários)

### Camada de Consumo e Visão

- **PHP 8.2+**
- **Laravel 10+ / 11+**
- **MySQL** (Via XAMPP)

### Infraestrutura & DevOps
- **Docker & Docker Compose** (Containerização do servidor Redis)
- **Redis 7** (Cache distribuído em memória)

## Fluxo

1. O Python consulta uma API de previsão do tempo.
2. Os dados são tratados e normalizados.
3. As informações são gravadas no banco de dados.
4. O Laravel lê esses dados.
5. A API e a interface exibem as previsões ao usuário.

## Evolução do Projeto

O desenvolvimento será dividido nos respectivos ciclos:

1 - Estrutura e Rotas Básicas: Criação da API inicial e configuração do ambiente.

2 - Integração Externa (Gateway): Consumo da API meteorológica da Open-Meteo via OpenMeteoGateway.

3 - Regras de Negócio e DTOs: Implementação do WeatherService e validação/modelagem de dados com Pydantic (WeatherResponse).

4 - Tratamento de Erros: Exceções customizadas (CityNotFoundException, ExternalApiException) e handlers globais.

5 - Testes Unitários e Injeção de Dependência: Isolamento de testes com FakeGateway e Inversão de Dependência (SOLID).

6 - Cache em Memória: Otimização com MemoryCache local para evitar requisições repetidas ao provedor externo.

7 - Integração com Laravel (PHP): Construção da camada de visão/consumo com WeatherController e WeatherApiService.

8 - Logs e Monitoramento: Registro estruturado de eventos e falhas no ecossistema Python.

9 - Cache Distribuído com Redis: Arquitetura distribuída com Docker, RedisCache, abstração via contrato (Protocol) e drivers por .env.

10 - Interface Blade, Validação e Padrões Laravel: Form Requests, API Resources, tratamento de exceções HTTP e Dashboard responsivo com Tailwind CSS.
---

## Como Rodar o Projeto

### Pré-requisitos
- PHP >= 8.2 & Composer
- XAMPP (Apache e MySQL)
- Python >= 3.10
- Docker & Docker Desktop

---

### 1. Banco de Dados (XAMPP)
1. Abra o XAMPP Control Panel.
2. Inicie os serviços do **Apache** e **MySQL**.
3. Certifique-se de que o banco de dados especificado no `.env` do Laravel está criado.

---

### 2. Infraestrutura (Redis via Docker)
# Subir o container do Redis em segundo plano (porta 6379)
`docker compose up -d`

---

### 3. Backend Python (FastAPI)
# 1. Acesse a pasta da API Python
`cd python-api`

# 2. Instale as dependências
`pip install fastapi uvicorn requests redis pydantic pytest`

# 3. Inicie o servidor da API na porta 8001
`uvicorn app.main:app --reload --port 8001`

---

### 4. Frontend & Consumo (Laravel)
# 1. Acesse a pasta do projeto Laravel
`cd previsao-tempo-projeto/previsao-tempo-projeto`

# 2. Instale as dependências e limpe as configurações em cache
```
composer install
php artisan key:generate
php artisan config:clear
```

# 3. Inicie o servidor de desenvolvimento na porta 8000
`php artisan serve`

---

### Testes Automatizados (Python)
# Dentro da pasta 'python-api', execute a suíte de testes unitários:
`python -m pytest`

## Licença

Projeto desenvolvido para fins de estudo.
