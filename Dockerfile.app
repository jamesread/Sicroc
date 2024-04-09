FROM localhost/sicroc/sicroc-base

COPY config.ini /etc/Sicroc/

VOLUME /etc/Sicroc/

COPY src/ ./src/

