FROM php:7.2-apache
COPY ./ /var/www/html/
EXPOSE 8080
CMD ["apache2ctl", "-D", "FOREGROUND"]



