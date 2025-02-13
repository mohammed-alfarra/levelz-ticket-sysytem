FROM php:8.2-fpm-alpine

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --update --no-cache libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    unzip \
    git

RUN apk add --no-cache --update --virtual buildDeps autoconf gcc make g++ zlib-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && pecl install igbinary \
    && pecl install redis \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) opcache \
    && docker-php-ext-enable igbinary \
    && docker-php-ext-enable redis \
    && apk del buildDeps

WORKDIR /var/www/app

EXPOSE 9000

CMD ["php-fpm"]
