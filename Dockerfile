FROM php:8.2-apache

# Extensión PDO MySQL + certificados para conexiones SSL a la BD en la nube
RUN apt-get update \
    && apt-get install -y --no-install-recommends ca-certificates \
    && docker-php-ext-install pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Zona horaria de Perú para fechas de tareo/asistencia
RUN echo "date.timezone=America/Lima" > /usr/local/etc/php/conf.d/timezone.ini

COPY . /var/www/html/
RUN rm -f /var/www/html/crear_admin.php \
    && chown -R www-data:www-data /var/www/html

# Render asigna el puerto en la variable PORT
CMD sed -i "s/80/${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf \
    && apache2-foreground
