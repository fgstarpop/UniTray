#!/bin/sh

USER_ID=${UID:-1000}
GROUP_ID=${GID:-1000}

echo "starting with USER_ID: ${USER_ID}, GROUP_ID: ${GROUP_ID}..."

getent passwd www-data || {
    addgroup --gid ${GROUP_ID} -S www-data
    adduser  --uid ${USER_ID} -g www-data -S -D --no-create-home www-data
}

if [[ "${GROUP_ID}" != $(id -g www-data) ]]; then
    groupmod -g ${GROUP_ID} www-data
fi

if [[ "${USER_ID}" != $(id -u www-data) ]]; then
    usermod -u ${USER_ID} -g www-data www-data
fi

chown -R www-data:www-data /work

if [[ $# -eq 0 ]]; then
    cd /work
    composer i
    composer dump-autoload
    php artisan config:cache

    rm -f storage/framework/views/*.php
    rm -f storage/logs/*
    chown -R 1000:www-data bootstrap/cache && \
    chown -R 1000:www-data storage && \
    mkdir -p storage/app/public/uploads/ckeditor && \
    chown -R 1000:www-data storage/app/public && \
    mkdir -p bootstrap/cache
    mkdir -p storage/framework/sessions
    mkdir -p storage/framework/cache
    mkdir -p storage/framework/views
    chmod 775 -R storage && \
    chmod 775 -R storage/app/public && \
    chmod 775 -R bootstrap/cache

    php artisan storage:link
    php artisan optimize
    php artisan optimize:clear
    php artisan migrate --force

    # initialize newrelic php-agent
    /usr/local/sbin/php-fpm
else
    exec "$@"
fi

