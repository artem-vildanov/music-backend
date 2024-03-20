FROM php:8.2-fpm

RUN apt update && apt install -y openssl

RUN docker-php-ext-install pdo pdo_mysql opcache

RUN printf "upload_max_filesize = 100M\n\
post_max_size = 100M\n" > /usr/local/etc/php/conf.d/php.ini

RUN mkdir -p /var/www/api-server/storage/framework \
    && mkdir -p /var/www/api-server/storage/framework/sessions \
    && mkdir -p /var/www/api-server/storage/framework/views \
    && mkdir -p /var/www/api-server/storage/framework/cache \
    && mkdir -p /var/www/api-server/storage/logs

RUN chown -R www-data:www-data /var/www/api-server/storage
