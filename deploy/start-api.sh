#!/bin/sh
# No set -e: on failure we stay alive so `coolify logs` can show what broke.
fail() {
    echo "START FAILED at: $1"
    tail -n 60 storage/logs/laravel.log 2>/dev/null
    sleep 3600
    exit 1
}

touch database/database.sqlite
php artisan storage:link || true
php artisan migrate --force || fail migrate

# Fresh container, fresh database: seed exactly once per boot.
if ! php artisan tinker --execute='exit(DB::table("users")->count() > 0 ? 0 : 1);' > /dev/null 2>&1; then
    php artisan db:seed --force || fail seed
fi

exec php artisan serve --host 0.0.0.0 --port 8000
