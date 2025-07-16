#!/usr/bin/env bash
# Render start script

echo "🚀 Iniciando aplicação Laravel..."

# Aguardar banco estar disponível
echo "⏳ Aguardando banco de dados..."
sleep 10

echo "✅ Banco de dados disponível!"

# Inicializar estrutura do banco
echo "📋 Inicializando estrutura do banco..."
php artisan db:init

# Executar migrações Laravel
echo "📋 Executando migrações Laravel..."
php artisan migrate --force

# Limpar e otimizar cache
echo "🧹 Limpando cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "⚡ Otimizando aplicação para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🎉 Aplicação iniciada com sucesso!"

# Iniciar servidor web
php artisan serve --host=0.0.0.0 --port=$PORT
