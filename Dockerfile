FROM php:8.2-fpm-alpine

# Instalar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www

# Copiar arquivos do projeto
COPY . .

# Instalar dependências do Laravel
RUN composer install

# Ajustar permissões de diretório de armazenamento
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expor a porta para o servidor web
EXPOSE 4000

CMD ["php-fpm"]

