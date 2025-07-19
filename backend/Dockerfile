# 1) Base PHP CLI (inclut php -S) et extensions
FROM php:8.2-cli

# 2) Installer les dépendances système et l’extension PostgreSQL
RUN apt-get update \
    && apt-get install -y libpq-dev unzip \
    && docker-php-ext-install pdo pdo_pgsql

# 3) Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4) Copier le code et installer les dépendances PHP
WORKDIR /var/www/html
COPY backend/ ./
RUN composer install --no-dev --optimize-autoloader

# 5) Exposer le port 80 et démarrer le serveur PHP intégré
EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]
