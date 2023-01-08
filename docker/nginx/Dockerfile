FROM nginx:alpine

LABEL maintainer="Jorge Juan Rodríguez Manzano"

COPY docker/nginx/nginx.conf /etc/nginx/

# If you're in China, or you need to change sources, will be set CHANGE_SOURCE to true in .env.

ARG CHANGE_SOURCE=false
RUN if [ ${CHANGE_SOURCE} = true ]; then \
    # Change application source from dl-cdn.alpinelinux.org to aliyun source
    sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/' /etc/apk/repositories \
;fi

RUN apk update \
    && apk upgrade \
    && apk --update add logrotate \
    && apk add --no-cache openssl \
    && apk add --no-cache bash

RUN apk add --no-cache curl

RUN set -x ; \
    addgroup -g 82 -S www-data ; \
    adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

ARG PHP_UPSTREAM_CONTAINER=php-fpm
ARG PHP_UPSTREAM_PORT=9000

# Enable SSL certificate for local development
COPY docker/nginx/self-signed-certificate.conf /etc/ssl/conf/
RUN openssl req -batch -x509 -nodes -days 365 -newkey rsa:4096 -keyout /etc/ssl/private/localhost.key -out /etc/ssl/certs/localhost.crt -config /etc/ssl/conf/self-signed-certificate.conf
RUN rm /etc/ssl/conf/self-signed-certificate.conf

# Create 'messages' file used from 'logrotate'
RUN touch /var/log/messages

# Copy 'logrotate' config file
COPY docker/nginx/logrotate/nginx /etc/logrotate.d/

# Remove the default conf
RUN rm /etc/nginx/conf.d/default.conf

# Enable Laravel Octane
COPY docker/nginx/sites/octane.conf /etc/nginx/sites-available/

# Enable Vite
COPY docker/nginx/sites/vite.conf /etc/nginx/sites-available/

# Add wait-for-it to wait for backend to be ready
COPY docker/wait-for-it.sh /usr/bin/wait-for-it.sh 
RUN chmod +x /usr/bin/wait-for-it.sh

ADD docker/nginx/startup.sh /opt/startup.sh
RUN sed -i 's/\r//g' /opt/startup.sh
CMD ["/bin/bash", "/opt/startup.sh"]

EXPOSE 80 443 5174