FROM bmoorman/ubuntu:bionic

ENV INTERVAL="10" \
    PLEX_HOST="localhost" \
    PLEX_PORT="32400" \
    PLEX_TOKEN="" \
    INFLUXDB_HOST="localhost" \
    INFLUXDB_PORT="8086" \
    INFLUXDB_USERNAME="" \
    INFLUXDB_PASSWORD="" \
    INFLUXDB_DATABASE="plex"

ARG DEBIAN_FRONTEND="noninteractive"

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
