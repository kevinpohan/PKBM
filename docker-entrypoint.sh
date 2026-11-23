#!/bin/sh
set -e

# Buat symbolic link storage
php artisan storage:link --force 2>/dev/null || true

# Jalankan migrasi database otomatis saat deploy
if [ -n "$DB_HOST" ]; then
    echo "Menjalankan migrasi database..."
    php artisan migrate --force || true
fi

# Optimasi caching Laravel untuk production
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Memulai server FrankenPHP di port 8000..."
exec frankenphp run --config /etc/caddy/Caddyfile
