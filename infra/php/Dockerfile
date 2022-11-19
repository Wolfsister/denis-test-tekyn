FROM php:8.1-rc-fpm-alpine
RUN apk --update --no-cache add git php81-xml
RUN docker-php-ext-install pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/bin
ENV PATH /root/.composer/vendor/bin:$PATH
WORKDIR /var/www
CMD composer install ;  php-fpm
EXPOSE 9000