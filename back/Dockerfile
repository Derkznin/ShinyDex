FROM dunglas/frankenphp:latest-php8.3

RUN install-php-extensions pdo pdo_pgsql opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /app
WORKDIR /app

RUN composer install

EXPOSE 8000

# Démarrer en mode dev sur port 8000, sans HTTPS
CMD ["frankenphp", "php-server", "--listen", ":8000", "--root", "/app/public"]