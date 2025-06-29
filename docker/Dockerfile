# Este Dockerfile será usado inicialmente para crear el proyecto Laravel
# y luego se usará para la imagen final de la aplicación.
FROM php:8.4-fpm-alpine

WORKDIR /var/www

# Instalar dependencias para las extensiones PHP
RUN apk add --no-cache \
    nginx \
    build-base \
    mysql-client \
    curl \
    libxml2-dev \
    oniguruma-dev \
    libzip-dev \
    gd-dev \
    icu-dev

# Instalar extensiones PHP
RUN docker-php-ext-install pdo_mysql opcache bcmath exif pcntl gd zip intl xml

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar permisos iniciales para el volumen compartido
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www

EXPOSE 9000
CMD ["php-fpm"]
