# ============================================
# SIAKAD - Production Dockerfile untuk Railway
# PHP 8.2 Apache + Laravel
# ============================================

# ---- Stage 1: Build assets (Frontend) ----
FROM node:20-alpine AS frontend
WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci
COPY . .
RUN npm run build

# ---- Stage 2: Runtime (PHP & Apache) ----
FROM php:8.2-apache

# Instalasi Ekstensi Sistem & PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    zip \
    zlib1g-dev \
    libpq-dev \
    && docker-php-ext-install gd zip pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Aktifkan Mod Apache Rewrite
RUN a2enmod rewrite

# Konfigurasi DocumentRoot ke folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Copy Source Code
COPY . .
COPY --from=frontend /app/public/build ./public/build

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Hak Akses Direktori (Permissions)
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Port (Railway menggunakan PORT environment variable)
EXPOSE 80

# Jalankan Migrasi dan Start Apache
CMD ["sh", "-c", "php artisan migrate --force && apache2-foreground"]
