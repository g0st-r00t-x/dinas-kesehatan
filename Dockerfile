# Stage 1: Build dependencies
FROM dunglas/frankenphp:1-php8.3-alpine AS build

# Install build dependencies
RUN apk add --no-cache \
    git curl zip unzip libzip-dev libpng-dev oniguruma-dev libxml2-dev \
    mysql-client icu-dev bash

# Allow composer as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Bun
RUN curl -fsSL https://bun.sh/install | bash
ENV PATH="/root/.bun/bin:$PATH"

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-scripts

# Copy frontend files
COPY package.json bun.lock ./
RUN bun install --frozen-lockfile

# Copy the rest of the application
COPY . .

# Build frontend assets
RUN bun run build

# Run composer scripts now that the app is available
RUN composer dump-autoload --optimize

# Stage 2: Final image
FROM dunglas/frankenphp:1-php8.3-alpine

# Install runtime dependencies only
RUN apk add --no-cache \
    libzip libpng oniguruma libxml2 mysql-client icu netcat-openbsd shadow \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd intl

# Set working directory
WORKDIR /app

# Create log directory for Caddy
RUN mkdir -p /var/log/caddy && chmod 755 /var/log/caddy

# Create Laravel directory structure
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Copy application from build stage
COPY --from=build /app /app

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy the Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# Copy and set up entrypoint
COPY entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

# Add healthcheck
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:8082/ || exit 1

# Expose ports for FrankenPHP and Reverb
EXPOSE 8082 8443 8081

# Set the entrypoint
ENTRYPOINT ["/docker-entrypoint.sh"]