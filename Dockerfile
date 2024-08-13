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

<<<<<<< HEAD
# Habilitar o módulo de reescrita do Apache para permitir URLs amigáveis no Laravel
=======
# Copiar arquivos de configuração personalizados para o Apache
COPY apache-config/apache2.conf /etc/apache2/apache2.conf
COPY apache-config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY apache-config/ports.conf /etc/apache2/ports.conf

# Dar permissão ao diretório de armazenamento e cache
RUN chown -R www-data:www-data /var/www/html/finance/storage /var/www/html/finance/bootstrap/cache /var/www/html/finance/database
RUN chmod -R 775 /var/www/html/finance/storage /var/www/html/finance/bootstrap/cache /var/www/html/finance/database

# Habilitar mod_rewrite no Apache
>>>>>>> 7a1a0c164f0e6bcc0c93f7908d9475ab97ff94f0
RUN a2enmod rewrite

# Alterar a porta do Apache para 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf
RUN sed -i 's/:80/:8080/' /etc/apache2/sites-available/000-default.conf

<<<<<<< HEAD
# Substituir a configuração do VirtualHost
RUN echo '<VirtualHost *:8080>' > /etc/apache2/sites-available/000-default.conf \
    && echo '    ServerAdmin webmaster@localhost' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    DocumentRoot /var/www/html/finance/public' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    <Directory /var/www/html/finance/public>' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    ErrorLog ${APACHE_LOG_DIR}/error.log' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    CustomLog ${APACHE_LOG_DIR}/access.log combined' >> /etc/apache2/sites-available/000-default.conf \
    && echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf
=======
# Expor a porta 80
EXPOSE 8080
>>>>>>> 7a1a0c164f0e6bcc0c93f7908d9475ab97ff94f0

# Expor a nova porta
EXPOSE 8080

 CMD ["apache2-foreground"]

