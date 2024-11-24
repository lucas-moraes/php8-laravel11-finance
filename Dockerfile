FROM php:8.2-apache

# Instalar dependências do Laravel e extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www/html/finance

# Copiar arquivos do projeto
COPY . .

# Instalar dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Ajustar permissões de diretório de armazenamento
RUN chown -R www-data:www-data /var/www/html/finance/storage /var/www/html/finance/bootstrap/cache \
    && chmod -R 775 /var/www/html/finance/storage /var/www/html/finance/bootstrap/cache

# Copiar arquivos de configuração personalizados para o Apache
COPY apache-config/apache2.conf /etc/apache2/apache2.conf
COPY apache-config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY apache-config/ports.conf /etc/apache2/ports.conf

# Dar permissão ao diretório de armazenamento e cache
RUN chown -R www-data:www-data /var/www/html/finance/storage /var/www/html/finance/bootstrap/cache /var/www/html/finance/database
RUN chmod -R 775 /var/www/html/finance/storage /var/www/html/finance/bootstrap/cache /var/www/html/finance/database

# Habilitar mod_rewrite no Apache
RUN a2enmod rewrite

# Expor a nova porta
EXPOSE 8080

 CMD ["apache2-foreground"]

