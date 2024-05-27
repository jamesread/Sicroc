FROM localhost/sicroc/sicroc-base

LABEL org.opencontainers.image.source https://github.com/jamesread/Sicroc

COPY config.ini /etc/Sicroc/

VOLUME /etc/Sicroc/

COPY src/ ./src/
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN mkdir -p ./src/private/libraries/
RUN composer install --no-dev

