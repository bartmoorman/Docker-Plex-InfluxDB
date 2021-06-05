FROM composer AS builder

WORKDIR /opt/composer

RUN composer require influxdb/influxdb-php

FROM bmoorman/ubuntu:focal

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
 && apt-get install --yes --no-install-recommends \
    php-cli \
    php-curl \
    php-xml \
    php-zip \
 && apt-get autoremove --yes --purge \
 && apt-get clean \
 && rm --recursive --force /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=builder /opt/composer/vendor/ /usr/local/bin/vendor/
COPY plex-influxdb/ /etc/plex-influxdb/
COPY bin/ /usr/local/bin/

CMD ["/etc/plex-influxdb/start.sh"]
