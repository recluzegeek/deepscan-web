# Stage 1: Build frontend assets
FROM node:20-alpine as frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Install PHP dependencies
FROM composer:latest as composer
WORKDIR /app
COPY composer.* ./
RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-req=ext-pcntl
COPY . .
RUN composer dump-autoload --optimize

# Stage 3: Production image
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring pcntl

# Install Redis extension
RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del pcre-dev $PHPIZE_DEPS

# Create required directories
RUN mkdir -p /var/log/supervisor \
    && mkdir -p /var/run \
    && mkdir -p /var/log/nginx \
    && chown -R www-data:www-data /var/log/supervisor \
    && chown -R www-data:www-data /var/log/nginx

# Configure PHP
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Configure Nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Configure Supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY --chown=www-data:www-data . .
COPY --from=composer /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

# Generate application key
RUN php artisan key:generate
RUN php artisan storage:link

# Set permissions
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start services
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]