FROM php:8.2-apache

# Instalar dependências do Laravel e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    nano \
    && docker-php-ext-install pdo pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . .

# Instalar dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Copiar arquivos de configuração personalizados para o Apache
COPY apache-config/apache2.conf /etc/apache2/apache2.conf
COPY apache-config/finance.conf /etc/apache2/sites-available/finance.conf
COPY apache-config/ports.conf /etc/apache2/ports.conf

# Dar permissão ao diretório de armazenamento e cache
RUN chown -R www-data:www-data /var/www/html/storage  /var/www/html/bootstrap/cache /var/www/html/database
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

RUN a2ensite finance.conf
RUN a2dissite 000-default.conf


# Habilitar mod_rewrite no Apache
RUN a2enmod rewrite

# Expor a nova porta
EXPOSE 4000

CMD ["apache2-foreground"]

