# Utiliza una imagen oficial de PHP 8.2 como base
FROM php:8.2-fpm

# Establece el directorio de trabajo en /app
WORKDIR /app

# Copia el código de la aplicación en el contenedor
COPY . /app

# Instala las extensiones de PHP requeridas
RUN apt-get update && apt-get install -y libzip-dev zip && \
    docker-php-ext-install mysqli pdo pdo_mysql

# Configura PHP para escuchar en el puerto 8080
RUN echo "listen = 8080" >> /usr/local/etc/php/php.ini

# Configura la conexión a la base de datos MySQL
ENV MYSQL_HOST 34.174.107.198
ENV MYSQL_DATABASE The-Fuentes_Corp
ENV MYSQL_USER the-fuentes-corp
ENV MYSQL_PASSWORD TheFuentes2024

# Expon el puerto 8080
EXPOSE 8080