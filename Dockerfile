# ============================================
# SIAKAD - Production Dockerfile untuk Railway/Render
# PHP 8.2 + Laravel 12
# ============================================

# ---- Stage 1: Build assets ----
FROM node:20-alpine AS frontend
WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

# ---- Stage 2: PHP app ----
FROM php:8.2-cli-alpine AS base
RUN apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    curl \
    mysql-client \
    postgresql-dev \
    $PHPIZE_DEPS \
    && docker-php-ext-install -j$(nproc) pdo_mysql pdo_pgsql zip pcntl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV PATH="/app/vendor/bin:$PATH"
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
COPY --from=frontend /app/public/build ./public/build
RUN composer dump-autoload --optimize

# Storage & cache writable
RUN chmod -R 775 storage bootstrap/cache

# ---- Stage 3: Runtime ----
FROM base AS runtime
# Prefer single process for Railway/Render (no artisan serve in subprocess)
ENV PORT=8080
EXPOSE 8080
CMD php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php -S 0.0.0.0:${PORT} -t public
