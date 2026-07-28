FROM php:8.2-apache

# Copy all PHP files into the Apache document root
COPY . /var/www/html/

# Reconfigure Apache to listen on Render's dynamic $PORT environment variable
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

EXPOSE 80
