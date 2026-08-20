#!/bin/sh
set -e

touch database/database.sqlite
php artisan storage:link || true
php artisan migrate --force

# Fresh container, fresh database: seed exactly once per boot.
if ! php artisan tinker --execute='exit(DB::table("users")->count() > 0 ? 0 : 1);' > /dev/null 2>&1; then
    php artisan db:seed --force
fi

exec php artisan serve --host 0.0.0.0 --port 8000
