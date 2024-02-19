FROM php:8.1.0-fpm-alpine

WORKDIR /work

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer

RUN apk add --no-cache \
        freetype-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        libpng-dev

# Install and update the packages.
RUN set -eux && \
    apk add --update-cache --no-cache --virtual=.build-dependencies tzdata && \
    apk add --no-cache git && \
    apk add --no-cache ffmpeg && \
    apk --update add imagemagick imagemagick-dev && \
    apk add shadow zip zlib-dev libzip-dev libpng-dev jpeg-dev autoconf libc-dev gcc make && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    apk del .build-dependencies && \
    docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg && \
    docker-php-ext-install -j$(nproc) bcmath pdo_mysql zip gd opcache && \
    pecl install -o -f imagick && \
    pecl install -o -f redis && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable imagick redis && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN docker-php-ext-install gd
RUN docker-php-ext-install exif

COPY ["./docker/site/app/config/php/php.ini", "/usr/local/etc/php/conf.d/php.ini"]
COPY ["./docker/site/app/scripts/docker-entrypoint.sh", "/docker-entrypoint.sh"]

COPY ["./apps/site", "./"]

# RUN composer install

EXPOSE 9000

ENTRYPOINT ["/docker-entrypoint.sh"]
