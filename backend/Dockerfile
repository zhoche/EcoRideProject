FROM php:8.2-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Install Composer (avant de copier le projet)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Symfony project (après Composer pour mieux utiliser le cache Docker)
COPY . .

# Configure Apache to use public/ as the DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Install PHP dependencies — cette ligne est très importante
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN mkdir -p var && chown -R www-data:www-data var

EXPOSE 80
