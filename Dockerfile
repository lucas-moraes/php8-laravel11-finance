FROM php:8.2-apache

# Instalar dependências do Laravel e extensões PHP necessárias
RUN docker-php-ext-install pdo

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www

# Copiar arquivos do projeto
COPY . .

# Instalar dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Ajustar permissões de diretório de armazenamento
 RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Habilitar o módulo de reescrita do Apache para permitir URLs amigáveis no Laravel
RUN a2enmod rewrite

# Expor a porta 80
EXPOSE 80

CMD ["apache2-foreground"]

