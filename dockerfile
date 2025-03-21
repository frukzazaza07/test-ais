# Use official PHP 8.2 FPM image (Laravel 10 requires PHP 8.1+)
FROM php:8.1-fpm

# Install system dependencies including specific ImageMagick version
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libmagickwand-dev \
    imagemagick \
    pkg-config \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions including Imagick with explicit version check
RUN printf "\n" | pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Verify Imagick installation
RUN php -m | grep imagick || echo "Imagick installation failed"

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install nodejs
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install

# Install Laravel dependencies
RUN composer install --ignore-platform-req=ext-zip --optimize-autoloader --no-dev

# ENV COMPOSER_ALLOW_SUPERUSER=1

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 9000 for PHP-FPM
EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]