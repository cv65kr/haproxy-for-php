ARG PHP_VERION=7.4.0

FROM jorge07/alpine-php:${PHP_VERION}-dev as php

ARG DOCKERIZE_VERSION=v0.6.1

RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz

COPY etc/artifact/start.sh /usr/local/bin/start.sh
COPY app/public /app/public
COPY test /test

WORKDIR /app

CMD ["/usr/local/bin/start.sh"]

FROM nginx:1.15-alpine as nginx

WORKDIR /app

COPY etc/artifact/nginx/nginx.conf /etc/nginx/conf.d/default.conf

COPY --from=php /app/public /app/public