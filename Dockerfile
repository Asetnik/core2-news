FROM php:8.3-fpm-bookworm

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libicu-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libxml2-dev \
        libonig-dev \
        libcurl4-openssl-dev \
        libmemcached-dev \
        libsasl2-dev \
        zlib1g-dev \
        libssl-dev \
        pkg-config; \
    docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" \
        zip \
        mbstring \
        curl \
        gd \
        intl \
        pdo \
        pdo_mysql \
        xml; \
    pecl install redis; \
    mkdir -p /tmp/memcached-src; \
    cd /tmp/memcached-src; \
    pecl download memcached-3.2.0; \
    tar -xzf memcached-3.2.0.tgz --strip-components=1; \
    phpize; \
    ./configure \
        --with-php-config=/usr/local/bin/php-config \
        --with-libmemcached-dir=/usr \
        --enable-memcached-sasl=no \
        --enable-memcached-session=yes; \
    make -j"$(nproc)"; \
    make install; \
    docker-php-ext-enable redis memcached; \
    rm -rf /tmp/memcached-src; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/project.com/htdocs