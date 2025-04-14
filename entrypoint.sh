#!/bin/sh
set -e

echo "===== Starting Laravel application setup ====="

echo "Checking and fixing directory permissions..."
[ -d "/app/bootstrap/cache" ] || mkdir -p /app/bootstrap/cache
[ -d "/app/storage/framework/sessions" ] || mkdir -p /app/storage/framework/sessions
[ -d "/app/storage/framework/views" ] || mkdir -p /app/storage/framework/views
[ -d "/app/storage/framework/cache" ] || mkdir -p /app/storage/framework/cache
[ -d "/app/storage/logs" ] || mkdir -p /app/storage/logs

# Set permission
echo "Setting correct file permissions..."
chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

# Wait for database connection if needed
if [ -n "$DB_HOST" ] && [ -n "$DB_PORT" ]; then
    echo "Waiting for database connection at $DB_HOST:$DB_PORT..."
    while ! nc -z "$DB_HOST" "$DB_PORT"; do
        sleep 0.5
    done
    echo "Database connected!"
fi

echo "Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Generating optimized caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Optimizing application..."
php artisan optimize:clear
php artisan optimize

# Run database migrations if enabled
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations and seeders..."
    php artisan migrate --force
    php artisan db:seed DataDukunganSeeder
    php artisan db:seed JenisSuratSeeder
    php artisan db:seed UnitKerjaSeeder
    php artisan db:seed ShieldSeeder
    php artisan db:seed UserSeeder
    echo "Database setup completed!"
fi

# Start Laravel Reverb server if enabled
if [ "$BROADCAST_DRIVER" = "reverb" ]; then
    echo "Starting Reverb WebSocket server on port $REVERB_PORT"
    php artisan reverb:start --port=$REVERB_PORT --host=0.0.0.0 &
fi

echo "===== Setup completed, starting FrankenPHP server ====="
# Start FrankenPHP server
exec frankenphp run --config /etc/caddy/Caddyfile