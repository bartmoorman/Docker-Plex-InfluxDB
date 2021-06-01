FROM bmoorman/ubuntu:focal

ARG DEBIAN_FRONTEND=noninteractive

WORKDIR /usr/local/bin

RUN apt-get update \
 && apt-get install --yes --no-install-recommends \
    php-cli \
    php-curl \
    php-xml \
    php-zip \
 && curl --silent --location --output /tmp/composer-setup.php "https://getcomposer.org/installer" \
 && echo "$(curl --silent --location "https://composer.github.io/installer.sig") /tmp/composer-setup.php" | sha384sum -c \
 && php /tmp/composer-setup.php --filename composer \
 && composer require influxdb/influxdb-php \
 && apt-get autoremove --yes --purge \
 && apt-get clean \
 && rm --recursive --force /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY plex-influxdb/ /etc/plex-influxdb/
COPY bin/ /usr/local/bin/

CMD ["/etc/plex-influxdb/start.sh"]
