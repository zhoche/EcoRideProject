FROM php:8.2-apache

# 1) OS & extensions + Apache rewrite
RUN apt-get update \
 && apt-get install -y git zip unzip libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql \
 && a2enmod rewrite

# 2) Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3) Définir le répertoire de travail
WORKDIR /var/www/html

# 4) Copier tout le code du backend
COPY backend/ ./

# 5) Autoriser le plugin symfony/flex
RUN composer config --global allow-plugins.symfony/flex true

# 6) Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# 7) Configurer Apache pour servir public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf

# 8) Ajuster les permissions
RUN mkdir -p var && chown -R www-data:www-data var

EXPOSE 80
