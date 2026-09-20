FROM php:8.1-apache

# ========================================
# System dependencies
# ========================================

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*


# ========================================
# PHP extensions
# ========================================

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip


# ========================================
# Composer
# ========================================

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# ========================================
# Apache configuration
# ========================================

RUN a2enmod rewrite


# Laravel must use /public
ENV APACHE_DOCUMENT_ROOT=/var/www/public

RUN sed -ri \
    -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf


# ========================================
# Application
# ========================================

WORKDIR /var/www

COPY . .

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader


# ========================================
# Permissions
# ========================================

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache


# ========================================
# Apache
# ========================================

EXPOSE 80

CMD ["apache2-foreground"]