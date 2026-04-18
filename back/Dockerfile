FROM dunglas/frankenphp:latest-php8.3

RUN install-php-extensions pdo pdo_pgsql opcache zip

RUN apt-get update && apt-get install -y git unzip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /app
WORKDIR /app

RUN composer install --no-scripts

EXPOSE 8000

CMD ["frankenphp", "php-server", "--listen", ":8000", "--root", "/app/public"]