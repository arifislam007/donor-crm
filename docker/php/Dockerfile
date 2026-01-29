# PHP Dockerfile for NGO Donor Management System
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    zip \
    postgresql-dev \
    postgresql-client \
    && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Create necessary directories
RUN mkdir -p storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data /var/www/html

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm", "-F"]
