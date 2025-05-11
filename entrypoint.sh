#!/bin/sh
set -e

echo "===== Starting Laravel application setup ====="

echo "Checking and fixing directory permissions..."
[ -d "/app/bootstrap/cache" ] || mkdir -p /app/bootstrap/cache
[ -d "/app/storage/framework/sessions" ] || mkdir -p /app/storage/framework/sessions
[ -d "/app/storage/framework/views" ] || mkdir -p /app/storage/framework/views
[ -d "/app/storage/framework/cache" ] || mkdir -p /app/storage/framework/cache
[ -d "/app/storage/logs" ] || mkdir -p /app/storage/logs

# Set correct permissions
echo "Setting correct file permissions..."
chown -R www-data:www-data /app
chmod -R 755 /app/public
chmod -R 775 /app/storage /app/bootstrap/cache

# Create storage symlink if it doesn't exist
if [ ! -L /app/public/storage ]; then
    echo "Creating storage symlink..."
    php artisan storage:link
fi

# Wait for database connection if needed
if [ -n "$DB_HOST" ] && [ -n "$DB_PORT" ]; then
    echo "Waiting for database connection at $DB_HOST:$DB_PORT..."
    timeout=60
    while ! nc -z "$DB_HOST" "$DB_PORT" && [ $timeout -gt 0 ]; do
        sleep 1
        timeout=$((timeout-1))
    done
    
    if [ $timeout -le 0 ]; then
        echo "Warning: Database connection timeout, continuing startup..."
    else
        echo "Database connected!"
    fi
fi

# Clear Laravel caches first
echo "Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
php artisan filament:optimize
php artisan icons:cache
php artisan filament:cache-components

# Generate optimized caches if in production mode
if [ "$APP_ENV" = "production" ]; then
    echo "Generating optimized caches..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan optimize
    php artisan filament:optimize
    php artisan icons:cache
    php artisan filament:cache-components
fi

# Run database migrations if enabled
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations and seeders..."
    php artisan migrate --force
    
    # Run seeders if they exist
    if [ -f "/app/database/seeders/DataDukunganSeeder.php" ]; then
        php artisan db:seed DataDukunganSeeder
    fi
    if [ -f "/app/database/seeders/JenisSuratSeeder.php" ]; then
        php artisan db:seed JenisSuratSeeder
    fi
    if [ -f "/app/database/seeders/UnitKerjaSeeder.php" ]; then
        php artisan db:seed UnitKerjaSeeder
    fi
    if [ -f "/app/database/seeders/ShieldSeeder.php" ]; then
        php artisan db:seed ShieldSeeder
    fi
    if [ -f "/app/database/seeders/UserSeeder.php" ]; then
        php artisan db:seed UserSeeder
    fi
    echo "Database setup completed!"
fi

# Start Laravel Reverb server if enabled
if [ "$BROADCAST_DRIVER" = "reverb" ]; then
    echo "Starting Reverb WebSocket server on port $REVERB_PORT"
    nohup php artisan reverb:start --port=${REVERB_PORT:-6001} --host=0.0.0.0 > /dev/null 2>&1 &
fi

# Double-check permissions one last time
echo "Final permission check..."
chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

echo "===== Setup completed, starting FrankenPHP server ====="
# Start FrankenPHP server
exec frankenphp run --config /etc/caddy/Caddyfile