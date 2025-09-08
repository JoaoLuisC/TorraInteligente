#!/usr/bin/env bash
# Script para testar a migração local para PostgreSQL

echo "Configurando ambiente de teste PostgreSQL..."

# Criar arquivo .env de teste
cp .env.example .env.test

# Configurar para PostgreSQL
sed -i 's/DB_CONNECTION=pgsql/DB_CONNECTION=pgsql/' .env.test
sed -i 's/DB_HOST=127.0.0.1/DB_HOST=127.0.0.1/' .env.test
sed -i 's/DB_PORT=5432/DB_PORT=5432/' .env.test
sed -i 's/DB_DATABASE=michelangelo_bd/DB_DATABASE=torra_test/' .env.test
sed -i 's/DB_USERNAME=postgres/DB_USERNAME=postgres/' .env.test

echo "Arquivo .env.test criado"
echo "Para testar localmente:"
echo "1. Instale PostgreSQL"
echo "2. Crie o banco 'torra_test'"
echo "3. Configure a senha do postgres no .env.test"
echo "4. Execute: php artisan migrate --env=test"
