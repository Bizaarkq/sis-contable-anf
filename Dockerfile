FROM php:7.4.2-apache
WORKDIR /app
EXPOSE 80
RUN apt-get update -y && apt-get install -y zip unzip libxml2-dev
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY server-apache.conf /etc/apache2/sites-available/000-default.conf
COPY . /app
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN a2enmod rewrite