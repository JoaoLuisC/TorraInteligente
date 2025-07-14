FROM php:8.2.20-apache

# Atualize os pacotes do sistema para corrigir vulnerabilidades
RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y libzip-dev unzip zip git curl \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get autoremove -y && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Habilita o mod_rewrite do Apache
RUN a2enmod rewrite

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copia configuração personalizada do Apache (aponta DocumentRoot para /var/www/html/public)
COPY ./apache/laravel.conf /etc/apache2/sites-available/000-default.conf

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia o projeto Laravel completo (não só o public/)
COPY . /var/www/html

# Corrige permissões para o Laravel funcionar corretamente
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
