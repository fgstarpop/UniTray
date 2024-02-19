#!/bin/sh

USER_ID=${UID:-1000}
GROUP_ID=${GID:-1000}

echo "starting with USER_ID: ${USER_ID}, GROUP_ID: ${GROUP_ID}..."

getent passwd supervisord || {
    addgroup --gid ${GROUP_ID} -S supervisord
    adduser  --uid ${USER_ID} -g supervisord -S -D --no-create-home supervisord
}

if [[ "${GROUP_ID}" != $(id -g supervisord) ]]; then
    groupmod -g ${GROUP_ID} supervisord
fi

if [[ "${USER_ID}" != $(id -u supervisord) ]]; then
    usermod -u ${USER_ID} -g supervisord supervisord
fi

chown -R supervisord:supervisord /work

if [[ $# -eq 0 ]]; then
    cd /work
    composer i
    # composer dump-autoload
    php artisan config:cache
    supervisord -c /etc/supervisord.conf
else
    exec "$@"
fi

