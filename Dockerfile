# ==========================================
# Stage 1: Build Frontend Assets (Vite)
# ==========================================
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# ==========================================
# Stage 2: Production PHP Image (FrankenPHP)
# ==========================================
FROM dunglas/frankenphp:1-php8.3-alpine

# Install ekstensi PHP yang dibutuhkan Laravel, DomPDF, Excel, dan QR
RUN install-php-extensions \
    pdo_mysql \
    gd \
    zip \
    bcmath \
    intl \
    opcache

# Install sertifikat CA untuk enkripsi TLS/SSL TiDB Cloud
RUN apk add --no-cache ca-certificates bash

# Copy Composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Konfigurasi environment container
ENV SERVER_NAME=":8000"
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=production
ENV APP_DEBUG=false

# Copy seluruh source code
COPY . .

# Copy Caddyfile konfigurasi server
COPY Caddyfile /etc/caddy/Caddyfile

# Copy file asset frontend hasil build dari Stage 1
COPY --from=frontend /app/public/build ./public/build

# Install dependency PHP tanpa paket dev
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Berikan hak akses writable ke folder storage & cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Copy script startup
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
