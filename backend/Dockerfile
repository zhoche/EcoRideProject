# backend/Dockerfile

FROM php:8.2-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy Symfony project
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions (crée dossier var pour logs/cache)
RUN mkdir -p var && chown -R www-data:www-data var

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
