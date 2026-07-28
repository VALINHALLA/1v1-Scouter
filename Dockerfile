FROM php:8.2-apache

# Copy all PHP files into the Apache document root
COPY . /var/www/html/

# Grant Apache ownership and read/execute permissions
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

# Reconfigure Apache to listen on Render's dynamic $PORT environment variable
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

EXPOSE 80
