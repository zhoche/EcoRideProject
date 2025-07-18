FROM php:8.2-apache

# 1) OS & extensions
RUN apt-get update \
 && apt-get install -y git zip unzip libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql

# 2) Apache rewrite
RUN a2enmod rewrite

# 3) Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4) Travailler dans /var/www/html
WORKDIR /var/www/html

# Débogage : liste récursive du contenu de /var/www/html
RUN ls -R /var/www/html

# 5) Copier et installer les deps
COPY backend/composer.json backend/composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# 6) Copier tout le code du backend
COPY backend/ ./

# 7) Configurer Apache pour /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf

# 8) Permissions
RUN mkdir -p var && chown -R www-data:www-data var

EXPOSE 80
