FROM php:7.4-apache

RUN apt update \
    && apt install -y vim zlib1g-dev g++ git libicu-dev zip libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libxml2-dev sudo librabbitmq-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install intl opcache pdo pdo_mysql zip gd bcmath soap sockets \
    && pecl install apcu \
    && pecl install xdebug-2.9.0 \
    && docker-php-ext-enable apcu xdebug

RUN a2enmod rewrite

RUN apt -y install sudo

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN useradd -rm -d /home/ubuntu -s /bin/bash -g root -G sudo -u 1000 ubuntu
RUN echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers
USER ubuntu

WORKDIR /var/www/html
