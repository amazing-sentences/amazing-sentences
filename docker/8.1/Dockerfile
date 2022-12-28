FROM alpine:3.17.0

LABEL maintainer="Jorge Juan Rodríguez Manzano"

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apk update \
    && apk add  bash curl ca-certificates zip unzip git supervisor libcap libpng-dev \
    && apk add  php81-cli \
                php81-dev \
                php81-gd \
                php81-curl \
                php81-imap \
                php81-mbstring \
                php81-xml \
                php81-zip \
                php81-bcmath \
                php81-soap \
                php81-intl \
                php81-ldap \
                php81-xdebug \
                # Alpine additional extensions
                php81-phar \
                php81-tokenizer \
                php81-dom \
                php81-fileinfo \
                php81-simplexml \
                php81-xmlwriter \
                php81-posix \
                php81-pdo \
                # Extensions under pecl namespace (different from Ubuntu)
                php81-pecl-msgpack \
                php81-pecl-igbinary \
                # Custom Sail choices
                php81-pecl-swoole \
                php81-pgsql \
                php81-pdo_pgsql \
                php81-redis \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && apk add nodejs npm \
    && npm install -g npm \
    && apk add yarn \
    && apk add postgresql-client \
    && rm -rf /tmp/* /var/cache/apk/*

RUN setcap "cap_net_bind_service=+ep" /usr/bin/php81

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /etc/php81/conf.d/99-sail.ini

COPY start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

RUN adduser --gecos "Sail" --ingroup www-data --disabled-password --shell /bin/bash --uid 1337 sail

RUN mkdir -p /var/log/supervisor && chmod -R o+rw /var/log/supervisor
RUN mkdir -p /var/run && chmod -R o+rw /var/run
RUN mkdir /.composer && chmod -R ugo+rw /.composer

USER sail

EXPOSE 8000

ENTRYPOINT ["start-container"]
