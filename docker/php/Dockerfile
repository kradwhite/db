FROM php:7.4-fpm
ADD php.ini /usr/local/etc/php/php.ini
WORKDIR /root/app
RUN apt-get update && apt-get upgrade && apt-get install -y libpq-dev wget unzip libyaml-dev \
&& docker-php-ext-install -j$(nproc) mysqli pdo_mysql pdo_pgsql \
&& pecl install xdebug yaml \
&& docker-php-ext-enable xdebug yaml
CMD ["php-fpm"]