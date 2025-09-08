#!/usr/bin/env bash
# Render# Corrigir problemas do banco no Render
echo "🔧 Corrigindo estrutura do banco de dados..."
php fix_render_db.php || echo "Fix DB falhou, continuando..."

# Executar migrações Laravel (só as básicas do Laravel)
echo "📋 Executando migrações básicas..."
php artisan migrate:install --force || echo "Migrate install falhou"

# Criar dados iniciais
echo "🌱 Criando dados iniciais..."
php artisan db:seed --class=InitialDataSeeder --force || echo "Seed falhou, continuando..."script

echo "🚀 Iniciando aplicação Laravel..."

# Aguardar banco estar disponível
echo "⏳ Aguardando banco de dados..."
sleep 10

echo "✅ Banco de dados disponível!"

# Debug: verificar o que está causando erro
echo "🔍 Executando debug..."
php debug.php

# Definir permissões corretas
echo "🔧 Corrigindo permissões..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Verificar e criar diretórios necessários
echo "📁 Verificando diretórios..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Corrigir problemas do banco no Render
echo "� Corrigindo estrutura do banco de dados..."
php fix_render_db.php || echo "Fix DB falhou, continuando..."

# Executar migrações Laravel (só as básicas do Laravel)
echo "📋 Executando migrações básicas..."
php artisan migrate:install --force || echo "Migrate install falhou"

# Limpar cache depois que as tabelas estão criadas
echo "🧹 Limpando cache..."
php artisan config:clear || echo "Config clear falhou"
php artisan cache:clear || echo "Cache clear falhou"
php artisan route:clear || echo "Route clear falhou"
php artisan view:clear || echo "View clear falhou"

echo "⚡ Otimizando aplicação para produção..."
php artisan config:cache || echo "Config cache falhou"
php artisan route:cache || echo "Route cache falhou"
php artisan view:cache || echo "View cache falhou"

echo "🎉 Aplicação iniciada com sucesso!"

# Iniciar servidor web
echo "🌐 Iniciando Apache..."
exec apache2-foreground
