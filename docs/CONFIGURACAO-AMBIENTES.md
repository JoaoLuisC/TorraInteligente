# Configurações para diferentes ambientes

## Desenvolvimento Local (PostgreSQL)
```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=michelangelo_bd
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

## Produção Render (PostgreSQL)
```bash
DB_CONNECTION=pgsql
# As variáveis abaixo são fornecidas automaticamente pelo Render
DATABASE_URL=postgresql://user:password@host:port/database
```

## Variáveis de Ambiente Obrigatórias no Render

### Aplicação
- `APP_NAME`: Nome da aplicação
- `APP_KEY`: Chave de criptografia (gerar com `php artisan key:generate --show`)
- `APP_ENV`: production
- `APP_DEBUG`: false
- `APP_URL`: URL da aplicação no Render

### Banco de Dados
- Conectar ao PostgreSQL do Render (automático)

### Cache e Sessões
- `SESSION_DRIVER`: database
- `CACHE_STORE`: database
- `QUEUE_CONNECTION`: database

### Logs
- `LOG_LEVEL`: error (para produção)

## Comandos de Migração

### Local
```bash
php artisan migrate
php artisan db:seed
```

### Render (automático no deploy)
```bash
php artisan migrate --force
```
