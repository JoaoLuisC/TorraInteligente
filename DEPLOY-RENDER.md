# Deploy Laravel no Render com PostgreSQL

## Passo a Passo Completo

### 1. Criar Banco PostgreSQL
1. Acesse [render.com](https://render.com) e faça login
2. Clique em **"New +"** → **"PostgreSQL"**
3. Configure:
   - **Name**: `torra-inteligente-db`
   - **Database Name**: `michelangelo_bd`
   - **User**: `postgres`
   - **Plan**: `Free`
   - **Region**: `Oregon`
4. Clique em **"Create Database"**
5. **IMPORTANTE**: Anote a URL de conexão gerada

### 2. Criar Web Service
1. Clique em **"New +"** → **"Web Service"**
2. Conecte seu repositório GitHub: `JoaoLuisC/TorraInteligente`
3. Configure:
   - **Name**: `torra-inteligente`
   - **Region**: `Oregon` (mesma do banco)
   - **Branch**: `main`
   - **Runtime**: `Docker`
   - **Build Command**: `chmod +x build.sh && ./build.sh`
   - **Start Command**: `chmod +x start.sh && ./start.sh`

### 3. Configurar Variáveis de Ambiente

**Adicione estas variáveis EXATAMENTE como estão:**

```
APP_NAME=Torra Inteligente
APP_ENV=production
APP_KEY=base64:rDN9zAoxyYPGFbbfMSsMnzEDm2JYxe2wzdjsDNbOTAQ=
APP_DEBUG=false
APP_URL=https://torra-inteligente.onrender.com
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=en
LOG_LEVEL=error
DB_CONNECTION=pgsql
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@torrainteligente.com
```

### 4. Conectar Banco de Dados
1. Na seção **"Environment Variables"**
2. Clique em **"Add from Database"**
3. Selecione o banco `torra-inteligente-db`
4. Isso adicionará automaticamente as variáveis do PostgreSQL

### 5. Deploy
1. Clique em **"Create Web Service"**
2. Aguarde o build (pode levar 5-10 minutos)
3. Acompanhe os logs para verificar se tudo está funcionando

## Estrutura de Arquivos Necessários ✅

- ✅ `Dockerfile` - Configurado para PostgreSQL
- ✅ `build.sh` - Script de build
- ✅ `start.sh` - Script de inicialização
- ✅ `Procfile` - Configuração de processo
- ✅ `.env.example` - Configurado para PostgreSQL
- ✅ `composer.json` - Dependências do Laravel

## Troubleshooting

### Se o build falhar:
- Verifique os logs no dashboard do Render
- Certifique-se de que o repositório está atualizado
- Verifique se os scripts têm permissão de execução

### Se a aplicação não iniciar:
- Verifique se as variáveis de ambiente estão corretas
- Verifique se o banco PostgreSQL está funcionando
- Verifique os logs da aplicação

### Se houver erro de migração:
- As migrações são executadas automaticamente no `start.sh`
- Verifique se as migrações são compatíveis com PostgreSQL
