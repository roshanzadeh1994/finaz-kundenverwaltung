# استفاده از image رسمی PHP با Apache
FROM php:8.2-apache

# فعال کردن افزونه‌های مورد نیاز
RUN docker-php-ext-install pdo pdo_mysql

# فعال‌سازی mod_rewrite برای Laravel
RUN a2enmod rewrite

# نصب Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# کپی کل پروژه داخل کانتینر
COPY . /var/www/html

# تنظیم DocumentRoot به public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# تنظیمات Apache برای استفاده از Laravel
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# دسترسی برای Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# نصب پکیج‌های Laravel
WORKDIR /var/www/html
RUN composer install
