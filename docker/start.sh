#!/bin/sh
set -e

cd /var/www/html

# ── 1. Parse DATABASE_URL → individual DB_* vars ─────────────────────────────
# Render provides: postgresql://user:pass@host:port/dbname?sslmode=require
if [ -n "$DATABASE_URL" ]; then
    # Strip scheme
    REST="${DATABASE_URL#*://}"
    # user:pass@host:port/dbname
    USERINFO="${REST%%@*}"
    HOSTINFO="${REST##*@}"

    DB_USERNAME_PARSED="${USERINFO%%:*}"
    DB_PASSWORD_PARSED="${USERINFO#*:}"

    HOSTPORT="${HOSTINFO%%/*}"
    DB_DATABASE_PARSED="${HOSTINFO#*/}"
    # Strip query string from dbname
    DB_DATABASE_PARSED="${DB_DATABASE_PARSED%%\?*}"

    DB_HOST_PARSED="${HOSTPORT%%:*}"
    DB_PORT_PARSED="${HOSTPORT##*:}"
    [ "$DB_PORT_PARSED" = "$HOSTPORT" ] && DB_PORT_PARSED="5432"

    export DB_CONNECTION="${DB_CONNECTION:-pgsql}"
    export DB_HOST="${DB_HOST:-$DB_HOST_PARSED}"
    export DB_PORT="${DB_PORT:-$DB_PORT_PARSED}"
    export DB_DATABASE="${DB_DATABASE:-$DB_DATABASE_PARSED}"
    export DB_USERNAME="${DB_USERNAME:-$DB_USERNAME_PARSED}"
    export DB_PASSWORD="${DB_PASSWORD:-$DB_PASSWORD_PARSED}"

    echo "[start.sh] DATABASE_URL parsed → $DB_HOST:$DB_PORT/$DB_DATABASE"
fi

# ── 2. Generate APP_KEY if missing ───────────────────────────────────────────
if [ -z "$APP_KEY" ]; then
    echo "[start.sh] Generating APP_KEY..."
    APP_KEY=$(php artisan key:generate --show --no-ansi)
    export APP_KEY
fi

# ── 3. Ensure storage directories exist and are writable ─────────────────────
mkdir -p storage/framework/{cache/data,sessions,views,testing} \
         storage/logs \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ── 4. Run database migrations ───────────────────────────────────────────────
echo "[start.sh] Running migrations..."
php artisan migrate --force --no-ansi

# ── 5. Seed only if the products table is empty ──────────────────────────────
PRODUCT_COUNT=$(php artisan tinker --execute="echo \App\Models\Product::count();" --no-ansi 2>/dev/null || echo "0")
if [ "$PRODUCT_COUNT" = "0" ]; then
    echo "[start.sh] Seeding database..."
    php artisan db:seed --force --no-ansi
fi

# ── 6. Storage symlink ───────────────────────────────────────────────────────
php artisan storage:link --force --no-ansi 2>/dev/null || true

# ── 7. Cache config, routes, views ───────────────────────────────────────────
php artisan config:cache  --no-ansi
php artisan route:cache   --no-ansi
php artisan view:cache    --no-ansi

echo "[start.sh] Starting PHP-FPM + Nginx via supervisord..."

# ── 8. Start supervisor (manages php-fpm + nginx) ────────────────────────────
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
