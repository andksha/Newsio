FROM phpdockerio/php74-fpm
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install supervisor imagemagick php7.4-imagick php7.4-cli php7.4-pgsql php7.4-sqlite nano php7.4-redis php7.4-bcmath php7.4-zmq \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY ./ /application

RUN rm -rf /application/docker && chgrp -R www-data storage bootstrap/cache && chmod -R ug+rwx storage bootstrap/cache