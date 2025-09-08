# Estrutura do Projeto TorraInteligente

## 📁 Estrutura Organizada

```
TorraInteligente/
├── app/                        # Código da aplicação Laravel
│   ├── Console/               # Comandos Artisan
│   ├── Http/                  # Controllers, Middleware, Requests
│   ├── Models/                # Models Eloquent
│   └── Providers/             # Service Providers
│
├── config/                     # Arquivos de configuração Laravel
│   ├── app.php
│   ├── database.php
│   ├── auth.php
│   └── ...
│
├── database/                   # Database relacionado
│   ├── migrations/            # Migrações do banco
│   ├── seeders/               # Seeders do banco
│   ├── factories/             # Factories para testes
│   ├── bd.sql                 # Schema principal
│   ├── clear_database.sql     # Script limpeza
│   └── Banco de Dados - querry/
│
├── docker/                     # Configurações Docker
│   ├── mysql/
│   └── postgresql/
│
├── public/                     # Assets públicos
│   ├── index.php              # Entry point
│   ├── css/
│   ├── js/
│   ├── images/
│   └── uploads/
│
├── resources/                  # Views e assets de desenvolvimento
│   ├── views/                 # Blade templates
│   ├── css/
│   └── js/
│
├── routes/                     # Definições de rotas
│   ├── web.php
│   └── console.php
│
├── scripts/                    # Scripts auxiliares
│   ├── build.sh              # Script de build
│   ├── start.sh              # Script de inicialização
│   ├── start_new.sh          # Script alternativo
│   ├── test-postgres.sh      # Teste PostgreSQL
│   ├── debug.php             # Script de debug
│   ├── check_timestamps.php  # Verificação timestamps
│   └── check_users.php       # Verificação usuários
│
├── tests/                      # Testes da aplicação
│   ├── Feature/              # Testes de feature
│   ├── Unit/                 # Testes unitários
│   └── manual/               # Testes manuais
│       ├── test_ajax.php
│       ├── test_db.php
│       ├── test_login.php
│       └── ...
│
├── docs/                       # Documentação
│   ├── CONFIGURACAO-AMBIENTES.md
│   ├── DEPLOY-RENDER.md
│   └── README-RENDER.md
│
├── logs/                       # Arquivos de log
│   ├── log.txt
│   └── log2.txt
│
├── temp/                       # Arquivos temporários
│   ├── cookies.txt
│   ├── cookies_login.txt
│   ├── test_cookies.txt
│   └── new_script.txt
│
├── storage/                    # Storage Laravel
├── bootstrap/                  # Bootstrap Laravel
├── vendor/                     # Dependências Composer
├── apache/                     # Configurações Apache
├── node_modules/               # Dependências Node.js
│
├── .env                        # Variáveis de ambiente
├── .env.example                # Exemplo de variáveis
├── composer.json               # Dependências PHP
├── package.json                # Dependências Node.js
├── docker-compose.yml          # Configuração Docker Compose
├── Dockerfile                  # Configuração Docker
├── artisan                     # CLI Laravel
├── phpunit.xml                 # Configuração testes PHPUnit
├── vite.config.js             # Configuração Vite
└── README.md                   # Documentação principal
```

## 🎯 Benefícios da Organização

### ✅ Separação Clara de Responsabilidades
- **Scripts**: Todos os scripts auxiliares em uma pasta específica
- **Testes**: Separação entre testes automatizados e manuais  
- **Documentação**: Centralizada na pasta `docs/`
- **Logs**: Organizados separadamente em `logs/`
- **Temporários**: Arquivos temporários isolados em `temp/`

### ✅ Estrutura Laravel Padrão
- Mantém a estrutura padrão do Laravel intacta
- Facilita manutenção e onboarding de novos desenvolvedores
- Compatível com ferramentas e IDEs

### ✅ Facilita Deploy e CI/CD
- Scripts organizados facilitam automação
- Configurações Docker centralizadas
- Logs separados para monitoramento

### ✅ Melhor Experiência de Desenvolvimento
- Navegação mais fácil no projeto
- Reduz confusão na localização de arquivos
- Melhora a produtividade da equipe

## 🚀 Comandos Principais

```bash
# Desenvolvimento
php artisan serve

# Build
./scripts/build.sh

# Inicialização
./scripts/start.sh

# Testes
php artisan test
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Docker
docker-compose up -d
```

## 📝 Próximos Passos Recomendados

1. **Atualizar CI/CD**: Ajustar pipelines para nova estrutura
2. **Documentar APIs**: Criar documentação das APIs no projeto
3. **Configurar IDE**: Ajustar configurações do IDE para nova estrutura
4. **Scripts de automação**: Criar mais scripts úteis em `scripts/`
5. **Linting**: Configurar ferramentas de qualidade de código

---

> **Nota**: Esta reorganização mantém toda funcionalidade existente enquanto melhora significativamente a organização e manutenibilidade do projeto.
