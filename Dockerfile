# ─────────────────────────────────────────────────────────────────────────────
# Stage 1: Node — build frontend assets
# ─────────────────────────────────────────────────────────────────────────────
FROM node:20-alpine AS node_builder

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY resources/ resources/
COPY public/      public/
COPY vite.config.js tailwind.config.js postcss.config.js jsconfig.json ./

RUN npm run build

# ─────────────────────────────────────────────────────────────────────────────
# Stage 2: PHP — install composer deps + final image
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.2-fpm-alpine

# ── System packages ───────────────────────────────────────────────────────────
RUN apk add --no-cache \
        nginx \
        supervisor \
        curl \
        git \
        unzip \
        libpng-dev \
        libzip-dev \
        libpq-dev \
        oniguruma-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        icu-dev \
        icu-libs \
    && rm -rf /var/cache/apk/*

# ── PHP extensions ────────────────────────────────────────────────────────────
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        zip \
        gd \
        bcmath \
        intl \
        opcache

# ── Composer ──────────────────────────────────────────────────────────────────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ── Install PHP dependencies ──────────────────────────────────────────────────
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --optimize-autoloader

# ── Copy application source ───────────────────────────────────────────────────
COPY . .

# ── Copy built frontend assets from stage 1 ──────────────────────────────────
COPY --from=node_builder /app/public/build ./public/build

# ── Regenerate autoloader with full app source ────────────────────────────────
RUN composer dump-autoload --optimize --no-dev --no-scripts

# ── Copy Docker config files ──────────────────────────────────────────────────
COPY docker/nginx.conf       /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php-fpm.conf     /usr/local/etc/php-fpm.d/www.conf
COPY docker/opcache.ini      /usr/local/etc/php/conf.d/opcache.ini
COPY docker/start.sh         /start.sh

# ── Permissions ───────────────────────────────────────────────────────────────
RUN chmod +x /start.sh \
    && mkdir -p storage/framework/{cache/data,sessions,views,testing} \
               storage/logs \
               bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD ["/start.sh"]
