FROM bmoorman/ubuntu:bionic

ARG DEBIAN_FRONTEND=noninteractive

WORKDIR /usr/local/bin

RUN apt-get update \
 && apt-get install --yes --no-install-recommends \
    composer \
    git \
    php-cli \
    php-curl \
    php-xml \
    php-zip \
 && composer require influxdb/influxdb-php \
 && apt-get autoremove --yes --purge \
 && apt-get clean \
 && rm --recursive --force /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY plex-influxdb/ /etc/plex-influxdb/
COPY bin/ /usr/local/bin/

CMD ["/etc/plex-influxdb/start.sh"]
