# Usa PHP 8.2 con FPM
FROM php:8.2-fpm

# Instala extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl git libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev && \
    docker-php-ext-install pdo pdo_mysql zip mbstring exif

# Copia Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crea el directorio de trabajo
WORKDIR /var/www

# Copia el contenido del proyecto (esto se sobrescribe con volumen al levantar)
COPY . .

# Da permisos adecuados
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]

