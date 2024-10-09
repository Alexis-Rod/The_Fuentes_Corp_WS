FROM php:7.2-apache
COPY ./ /var/www/html/
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data
ENV APACHE_LISTEN_PORT=8080
EXPOSE 8080
CMD ["apache2ctl", "-D", "FOREGROUND"]


