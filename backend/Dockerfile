FROM php:8.2-apache

# 1) OS & extensions + Apache rewrite
RUN apt-get update \
 && apt-get install -y git zip unzip libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql \
 && a2enmod rewrite

# 2) Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3) Définir le dossier de travail
WORKDIR /var/www/html

# 4) Copier tout le code du backend (y compris composer.json)
COPY backend/ ./

# 5) Installer les dépendances PHP dans ./vendor
RUN composer install --no-dev --optimize-autoloader

# 6) Configurer Apache pour /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf

# 7) Permissions
RUN mkdir -p var && chown -R www-data:www-data var

EXPOSE 80
