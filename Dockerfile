FROM php:8.3-cli
RUN apt-get update -y && apt-get install -y libzip-dev unzip
RUN docker-php-ext-install zip pdo pdo_mysql
COPY . /app
WORKDIR /app
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev
CMD php artisan serve --host=0.0.0.0 --port=10000