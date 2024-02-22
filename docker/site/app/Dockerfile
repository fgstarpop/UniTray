FROM node:18-alpine AS node
FROM php:7.4-fpm-alpine

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
    apk add --no-cache --virtual .build-deps g++ && \
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

COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/include/node /usr/local/include/node
COPY --from=node /usr/local/share/man/man1/node.1 /usr/local/share/man/man1/node.1
COPY --from=node /usr/local/share/doc/node /usr/local/share/doc/node
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /opt/ /opt/
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm
RUN ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx
RUN ln -s /opt/yarn-$(ls /opt/ | grep yarn | sed 's/yarn-//')/bin/yarn /usr/local/bin/yarn
RUN ln -s /opt/yarn-$(ls /opt/ | grep yarn | sed 's/yarn-//')/bin/yarnpkg /usr/local/bin/yarnpkg

COPY ["./docker/site/app/config/php/php.ini", "/usr/local/etc/php/conf.d/php.ini"]
COPY ["./docker/site/app/scripts/docker-entrypoint.sh", "/docker-entrypoint.sh"]

COPY ["./apps/site", "./"]

# RUN composer install

EXPOSE 9000

ENTRYPOINT ["/docker-entrypoint.sh"]
