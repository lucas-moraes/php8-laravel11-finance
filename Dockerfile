# Use a imagem oficial do PHP compatível com ARM como base
FROM arm32v7/php:8.2-apache

# Instalar extensões necessárias do PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    nano \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar o diretório de trabalho
WORKDIR /var/www/html

# Copiar o conteúdo do projeto para o diretório de trabalho
COPY . .

# Instalar as dependências do Composer
RUN composer install --optimize-autoloader --no-dev

# Otimizar a configuração e rotas
RUN php artisan config:cache
RUN php artisan route:cache

# Remover arquivos de configuração antigos do Apache
# RUN rm /etc/apache2/apache2.conf
RUN rm /etc/apache2/ports.conf

# Copiar arquivos de configuração personalizados para o Apache
# COPY apache-config/apache2.conf /etc/apache2/apache2.conf
COPY apache-config/laravel.conf /etc/apache2/sites-available/laravel.conf
COPY apache-config/ports.conf /etc/apache2/ports.conf

# Dar permissão ao diretório de armazenamento e cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Habilitar mod_rewrite no Apache
RUN a2dissite 000-default.conf
RUN a2ensite laravel.conf
RUN a2enmod rewrite

# Expor a porta 80
EXPOSE 80

# Comando para iniciar o Apache
CMD ["apache2-foreground"]

