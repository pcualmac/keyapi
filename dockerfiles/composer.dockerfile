FROM php:8.3-cli

# Install necessary packages and PHP extensions
RUN apt-get update && apt-get install -y \
    libexif-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure exif \
    && docker-php-ext-install exif zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/*


# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

  

WORKDIR /var/www/html

# # # Set permissions for Laravel.
# RUN chown -R www-data:www-data storage bootstrap/cache

# Install Codeception
RUN composer require codeception/codeception


# Install Laravel dependencies using Composer.
RUN composer require spatie/laravel-medialibrary
RUN composer require codeception/module-laravel codeception/module-rest codeception/module-webdriver codeception/module-asserts --dev
RUN composer require predis/predis



ENTRYPOINT [ "composer" ]