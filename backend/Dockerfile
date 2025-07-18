FROM php:8.2-apache

# 1) OS & PHP extensions + Apache rewrite
RUN apt-get update \
 && apt-get install -y git zip unzip libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql \
 && a2enmod rewrite

# 2) Bring in Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3) Set working dir
WORKDIR /var/www/html

# 4) Copy your entire backend (including composer.json & lock)
COPY backend/ ./

# 5) Allow symfony/flex plugin & install deps (this generates vendor/autoload_runtime.php)
RUN composer config --global allow-plugins.symfony/flex true \
 && composer install --no-dev --optimize-autoloader

# 6) Configure Apache to serve public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
     /etc/apache2/sites-available/000-default.conf

# 7) Fix permissions
RUN mkdir -p var && chown -R www-data:www-data var

EXPOSE 80
