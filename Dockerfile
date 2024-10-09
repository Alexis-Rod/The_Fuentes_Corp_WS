FROM php:7.2-apache

# Agregar la configuración de MySQL
ENV MYSQL_HOST=34.174.107.198
ENV MYSQL_USER=the-fuentes-corp
ENV MYSQL_PASSWORD=TheFuentes2024
ENV MYSQL_DB=The-Fuentes_Corp

# Copiar el archivo de configuración de MySQL
COPY my.ini /etc/mysql/my.cnf

# Exponer el puerto de MySQL
EXPOSE 3306

# Instalar el módulo php-mysql
RUN apt-get update && apt-get install -y php-mysql

# Configurar Apache para que utilice la base de datos MySQL
RUN sed -i 's/localhost/${MYSQL_HOST}/g' /etc/apache2/apache2.conf

# Copiar el código de la aplicación
COPY ./ /var/www/html/

# Configurar Apache para que utilice el puerto 8080
ENV APACHE_LISTEN_PORT=8080
EXPOSE 8080

# Iniciar Apache
CMD ["apache2ctl", "-D", "FOREGROUND"]


