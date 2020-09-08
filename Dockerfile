FROM php:7.4-apache
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite && mkdir /app && chown -R www-data:www-data /app && a2ensite 000-default && service apache2 restart
