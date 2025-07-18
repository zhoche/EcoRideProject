FROM php:8.2-apache

# 0) Forcer Symfony en prod (pour PHP et CLI)
ENV APP_ENV=prod
ENV APP_DEBUG=0

# 1) Autoriser Composer en root et charger les plugins Flex & Runtime
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_ALLOW_PLUGINS="symfony/flex true symfony/runtime true"

# 2) OS & extensions + Apache rewrite + mod_env
RUN apt-get update \
 && apt-get install -y git zip unzip libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql \
 && a2enmod rewrite env

# 3) Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4) Répertoire de travail
WORKDIR /var/www/html

# 5) Copier le code
COPY backend/ ./

# 6) Installer les dépendances (production uniquement)
RUN composer install --no-dev --optimize-autoloader

# DEBUG : lister vendor après install
RUN ls -R /var/www/html/vendor

# 7) Configurer Apache pour servir public/ + SetEnv
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public\n    # set Symfony env to prod\n    SetEnv APP_ENV prod\n    SetEnv APP_DEBUG 0|' \
    /etc/apache2/sites-available/000-default.conf

# 8) Permissions
RUN mkdir -p var && chown -R www-data:www-data var

EXPOSE 80

CMD ["apache2-foreground"]
