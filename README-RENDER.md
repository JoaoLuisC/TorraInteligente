# Deploy no Render - Torra Inteligente

Este guia descreve como fazer o deploy da aplicação Laravel no Render com PostgreSQL.

## Pré-requisitos

1. Conta no [Render](https://render.com)
2. Repositório no GitHub com o código da aplicação

## Passos para Deploy

### 1. Preparar o Repositório

Certifique-se de que os seguintes arquivos estão no repositório:
- `build.sh` - Script de build
- `start.sh` - Script de inicialização  
- `Procfile` - Configuração do processo
- `render.yaml` - Configuração do Render (opcional)
- `Dockerfile` - Configuração do Docker

### 2. Configurar PostgreSQL

1. No dashboard do Render, clique em "New +"
2. Selecione "PostgreSQL"
3. Configure:
   - **Name**: `torra-inteligente-db`
   - **Database Name**: `michelangelo_bd`
   - **User**: `postgres`
   - **Region**: Escolha a mesma região do web service
4. Clique em "Create Database"

### 3. Configurar Web Service

1. No dashboard do Render, clique em "New +"
2. Selecione "Web Service"
3. Conecte seu repositório GitHub
4. Configure:
   - **Name**: `torra-inteligente`
   - **Region**: Mesma do banco de dados
   - **Branch**: `main`
   - **Runtime**: `Docker`
   - **Build Command**: `chmod +x build.sh && ./build.sh`
   - **Start Command**: `chmod +x start.sh && ./start.sh`

### 4. Configurar Variáveis de Ambiente

No painel do Web Service, vá para "Environment" e adicione:

```
APP_NAME=Torra Inteligente
APP_ENV=production
APP_KEY=base64:SuaChaveGeradaPeloArtisan
APP_DEBUG=false
APP_URL=https://sua-app.onrender.com

APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=en

LOG_LEVEL=error

DB_CONNECTION=pgsql
DATABASE_URL=postgresql://user:password@host:port/database

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=log
```

**Importante**: 
- Gere a `APP_KEY` rodando `php artisan key:generate --show` localmente
- A `DATABASE_URL` será fornecida automaticamente pelo PostgreSQL do Render

### 5. Conectar ao Banco de Dados

1. No painel do Web Service, vá para "Environment"
2. Clique em "Add from Database" 
3. Selecione o banco PostgreSQL criado
4. Isso adicionará automaticamente as variáveis do banco

### 6. Deploy

1. Clique em "Create Web Service"
2. O Render automaticamente:
   - Fará o build da aplicação
   - Executará as migrações
   - Iniciará o servidor

### 7. Pós-Deploy

Após o primeiro deploy bem-sucedido:

1. Acesse o shell do serviço no Render
2. Execute os seeders (se necessário):
   ```bash
   php artisan db:seed
   ```

## Comandos Úteis

### Localmente (Desenvolvimento)

```bash
# Gerar chave da aplicação
php artisan key:generate

# Executar migrações
php artisan migrate

# Executar seeders
php artisan db:seed

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### No Render (Produção)

```bash
# Acessar shell do serviço
# (Via dashboard do Render)

# Executar migrações
php artisan migrate --force

# Executar seeders
php artisan db:seed --force

# Verificar status
php artisan optimize
```

## Troubleshooting

### Erro de Conexão com Banco
- Verifique se as variáveis de ambiente do banco estão corretas
- Certifique-se de que o web service está na mesma região do banco

### Erro de Build
- Verifique os logs de build no dashboard do Render
- Certifique-se de que `build.sh` tem permissões de execução

### Erro de Migração
- Verifique se as migrações são compatíveis com PostgreSQL
- Use tipos de dados compatíveis (ex: `string` ao invés de `varchar`)

### Performance
- Para melhor performance, considere usar Redis para cache e sessions
- Configure um CDN para assets estáticos

## Monitoramento

- Use os logs do Render para monitorar erros
- Configure alertas para downtime
- Monitore o uso de recursos (CPU, memória, banco)
