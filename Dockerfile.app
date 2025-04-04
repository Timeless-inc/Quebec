FROM php:8.2-cli

# Instalar dependências
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Instalar extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar arquivos do projeto (exceto os ignorados no .dockerignore)
COPY . /var/www

# Instalar dependências do Composer
RUN composer install --no-interaction --optimize-autoloader

# Configurar permissões para storage e cache
RUN chmod -R 775 storage bootstrap/cache

# Configurar link simbólico storage (ao invés de copiar o link simbólico problemático)
RUN rm -rf public/storage && php artisan storage:link

# Expor porta 8000
EXPOSE 8000

# Comando padrão
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]