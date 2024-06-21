FROM php:8.1-fpm

RUN apt update && apt install -y openssl

RUN apt-get update && apt-get install -y \
        sudo \
        libonig-dev \
        libzip-dev \
        curl \
        wget \
        git \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        mc htop telnet iputils-ping \
        libcurl4-openssl-dev \
        pkg-config \
        libssl-dev \
    && docker-php-ext-install -j$(nproc) mbstring iconv mysqli pdo_mysql zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) sockets
RUN pecl install mongodb && docker-php-ext-enable mongodb && \
    docker-php-ext-install opcache && docker-php-ext-enable opcache


RUN printf "upload_max_filesize = 100M\n\
post_max_size = 100M\n" > /usr/local/etc/php/conf.d/php.ini

RUN mkdir -p /var/www/api-server/storage/framework \
    && mkdir -p /var/www/api-server/storage/framework/sessions \
    && mkdir -p /var/www/api-server/storage/framework/views \
    && mkdir -p /var/www/api-server/storage/framework/cache \
    && mkdir -p /var/www/api-server/storage/logs

RUN chown -R www-data:www-data /var/www/api-server/storage
