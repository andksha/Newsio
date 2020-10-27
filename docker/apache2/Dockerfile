FROM php:7.4-apache

RUN apt-get -y update

#RUN apt-get -y --no-install-recommends install postgresql-dev

RUN docker-php-ext-install pdo pdo_mysql bcmath

RUN apt-get update \
    && apt-get -y --no-install-recommends install imagemagick php7.4-imagick php7.4-cli php7.4-pgsql php7.4-sqlite nano php-redis php7.4-bcmath php7.4-zmq \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

#install imagick
#RUN apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*
#RUN pecl install imagick
#RUN docker-php-ext-enable imagick

RUN a2enmod rewrite
RUN a2dissite 000-default.conf
COPY docker/apache2/config/main.conf /etc/apache2/sites-available/main.conf
RUN a2ensite main.conf
RUN service apache2 restart
