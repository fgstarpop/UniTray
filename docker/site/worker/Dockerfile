FROM php:7.4-fpm-alpine

WORKDIR /work

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer

RUN set -eux && \
    apk add --update-cache --no-cache --virtual=.build-dependencies tzdata && \
    apk add --no-cache git && \
    apk add shadow zip zlib-dev libzip-dev libpng-dev jpeg-dev autoconf supervisor libc-dev gcc make && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    apk del .build-dependencies && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install -j$(nproc) bcmath pdo_mysql zip gd opcache && \
    pecl install -o -f redis && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

COPY ["./docker/site/worker/config/php/php.ini", "/usr/local/etc/php/conf.d/php.ini"]
COPY ["./docker/site/worker/config/supervisor/supervisord.conf", "/etc/supervisord.conf"]
# COPY ["./docker/site/worker/config/supervisor/cron.conf", "/etc/supervisord.d/cron.conf"]
COPY ["./docker/site/worker/config/supervisor/emails.conf", "/etc/supervisord.d/emails.conf"]
COPY ["./docker/site/worker/config/supervisor/commands.conf", "/etc/supervisord.d/commands.conf"]
COPY ["./docker/site/worker/config/supervisor/redis.conf", "/etc/supervisord.d/redis.conf"]
COPY ["./docker/site/worker/config/supervisor/scheduler.conf", "/etc/supervisord.d/scheduler.conf"]
COPY ["./docker/site/worker/scripts/docker-entrypoint.sh", "/docker-entrypoint.sh"]

COPY ["./apps/site", "./"]

# RUN composer install

EXPOSE 9000

ENTRYPOINT ["/docker-entrypoint.sh"]
