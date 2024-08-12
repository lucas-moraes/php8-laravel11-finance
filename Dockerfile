FROM php:8.2-apache

# Instalar dependências do Laravel e extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

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

# Alterar a porta do Apache para 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf
RUN sed -i 's/:80/:8080/' /etc/apache2/sites-available/000-default.conf

# Substituir a configuração do VirtualHost
RUN echo '<VirtualHost *:8080> \
    ServerAdmin webmaster@localhost \
    DocumentRoot /var/www/public \
    <Directory /var/www/public> \
        Options Indexes FollowSymLinks \
        AllowOverride All \
        Require all granted \
    </Directory> \
    ErrorLog ${APACHE_LOG_DIR}/error.log \
    CustomLog ${APACHE_LOG_DIR}/access.log combined \
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf


# Expor a nova porta
EXPOSE 8080

 CMD ["apache2-foreground"]

