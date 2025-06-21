FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        zip \
    && rm -rf /var/lib/apt/lists/*

RUN composer install


RUN apt-get update && apt-get install -y netcat-openbsd


COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
CMD ["sh", "-c", "php artisan config:clear && php artisan migrate --force && apache2-foreground"]
