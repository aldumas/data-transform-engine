FROM php:8.1.4-cli
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN sed 's@^;error_log = php_errors.log$@error_log = /var/log/errors.log@' /usr/local/etc/php/php.ini-production > /usr/local/etc/php/php.ini
WORKDIR /var/www/html
COPY . .
RUN apt update && apt install -y --no-install-recommends libzip-dev && docker-php-ext-install zip
RUN composer install
CMD ["php", "engine.php", "-c", "sample", "./data/sample.csv"]
