# Usa a imagem oficial do PHP com suporte a extensões
FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho dentro do contêiner
WORKDIR /var/www

# Copia os arquivos do projeto para o contêiner
COPY . .

# Instala dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Instala Node.js e dependências do frontend
RUN npm install && npm run build

# Permite permissões corretas para o Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expõe a porta do servidor PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
