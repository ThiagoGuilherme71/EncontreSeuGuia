# Imagem oficial do PHP com PHP-FPM
FROM php:8.2-fpm

# Dependencias do sistema e extensoes do PHP usadas pelo Laravel
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    curl \
    ca-certificates \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Node.js 22 LTS (o pacote do Debian fica varias versoes atras do que o Vite pede)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Limites de upload (fotos das aventuras)
COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www

# O codigo tambem e montado como volume pelo docker-compose; este COPY garante
# que a imagem funcione sozinha, e o entrypoint instala vendor/ e node_modules/
# em tempo de subida (o bind mount sobrescreveria qualquer install feito aqui).
COPY . .

COPY docker/lib.sh /usr/local/lib/docker-app/lib.sh
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/vite-entrypoint.sh /usr/local/bin/vite-entrypoint.sh
COPY docker/php/db-check.php /usr/local/bin/db-check.php
RUN chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/vite-entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
