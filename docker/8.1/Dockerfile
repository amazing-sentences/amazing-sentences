FROM alpine:3.17.0

LABEL maintainer="Jorge Juan Rodríguez Manzano"

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apk update \
    && apk add  bash curl ca-certificates zip unzip git supervisor libcap libpng-dev \
                # Dev Containers development
                gnupg openssh-client \
                # PHP
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
                # Alpine additional extensions
                php81-phar \
                php81-tokenizer \
                php81-dom \
                php81-fileinfo \
                php81-simplexml \
                php81-xmlwriter \
                php81-posix \
                php81-pdo \
                php81-iconv \
                # Extensions under pecl namespace (different from Ubuntu)
                php81-pecl-msgpack \
                php81-pecl-igbinary \
                php81-pecl-xdebug \
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

# Enable Xdebug for code coverage
RUN rm /etc/php81/conf.d/50_xdebug.ini
COPY docker/8.1/99_xdebug.ini /etc/php81/conf.d/

# Container startup requirements
COPY docker/8.1/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/8.1/php.ini /etc/php81/conf.d/99-sail.ini

COPY docker/8.1/start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

RUN adduser --gecos "Sail" --ingroup www-data --disabled-password --shell /bin/bash --uid 1337 sail

RUN mkdir -p /var/log/supervisor && chmod -R o+rw /var/log/supervisor
RUN mkdir -p /var/run && chmod -R o+rw /var/run
RUN mkdir /.composer && chmod -R ugo+rw /.composer
# End of Container startup requirements

COPY --chown=sail:www-data . ./
RUN chown sail:www-data .

# Azure Web App mounts App Service Plan Persistent Storage into /home
# (requires WEBSITES_ENABLE_APP_SERVICE_STORAGE = true)
# https://learn.microsoft.com/en-us/azure/app-service/configure-custom-container?pivots=container-linux&tabs=debian#use-persistent-shared-storage
RUN mkdir -p /home/laravel \
    && chown -R sail:www-data /home/laravel \
    && mv /var/www/html/storage /home/laravel/ \
    && ln -sf /home/laravel/storage /var/www/html/

# Add wait-for-it to wait for database to be ready
COPY docker/wait-for-it.sh /usr/local/bin/wait-for-it.sh 
RUN chmod +x /usr/local/bin/wait-for-it.sh

USER sail
RUN composer install --no-progress --no-interaction
RUN npm ci

EXPOSE 8000

CMD ["/bin/bash", "start-container"]
