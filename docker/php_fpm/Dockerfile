FROM phpdockerio/php74-fpm
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install imagemagick php7.4-imagick php7.4-cli php7.4-pgsql php7.4-sqlite nano php-redis php7.4-bcmath php7.4-zmq \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY ./docker/php_fpm/php-ini-overrides.ini /etc/php/7.3/fpm/conf.d/99-overrides.ini
COPY ./ /application

RUN rm -rf /application/docker
