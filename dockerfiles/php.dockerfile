# Use an official PHP image with Apache as the base image.
FROM php:8.3-apache


# Install required system dependencies
RUN apt-get update \
    && apt-get install -y \
        apt-utils \
        vim \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        zip \
        unzip \
        libonig-dev \
        libxml2-dev \
        libssl-dev \
        libcurl4-openssl-dev \
        pkg-config \
        libmagickwand-dev \
        exiftool \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd exif zip pdo_mysql curl xml bcmath \
    # && pecl install imagick \
    # && docker-php-ext-enable imagick \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl -L -o /tmp/imagick.tar.gz https://github.com/Imagick/imagick/archive/7088edc353f53c4bc644573a79cdcd67a726ae16.tar.gz \
    && tar --strip-components=1 -xf /tmp/imagick.tar.gz \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini \
    && rm -rf /tmp/* 

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN apt-get update -y
RUN apt-get install -y iputils-ping
    
COPY ./apikey /var/www/html

# # Install PHP extensions.
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql sodium

# Install PHP enable.
RUN docker-php-ext-enable gd pdo_mysql sodium

# # Install Composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create a directory for your Laravel application.
WORKDIR /var/www/html



# Set permissions for Laravel.
RUN chown -R www-data:www-data storage bootstrap/cache

# You can add any additional configurations or commands required for Laravel 10 here.
