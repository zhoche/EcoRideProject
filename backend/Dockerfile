FROM php:8.2-cli

# Install system dependencies and PostgreSQL extension
RUN apt-get update \
    && apt-get install -y libpq-dev unzip git \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Prepare work directory
WORKDIR /var/www/html

# Copy composer files first for better cache utilization
COPY backend/composer.json backend/composer.lock ./

# Install PHP dependencies before copying application source
RUN composer install --no-dev --optimize-autoloader

# Now copy the rest of your application code ("backend" here)
COPY backend/ ./

# Expose port and run PHP's built-in serverFROM php:8.2-cli

# Install system dependencies and PostgreSQL extension
RUN apt-get update \
    && apt-get install -y libpq-dev unzip git \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Prepare work directory
WORKDIR /var/www/html

# Copy composer files first for better cache utilization
COPY backend/ ./

# Install PHP dependencies before copying application source
RUN composer install --no-dev --optimize-autoloader

# Now copy the rest of your application code ("backend" here)

# Expose port and run PHP's built-in server
EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]

EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]
