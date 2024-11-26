FROM ubuntu:22.04

LABEL maintainer="Hugo Costa"

ARG WWWGROUP
ARG MYSQL_CLIENT="mysql-client"

WORKDIR /var/www/html

ENV TZ=UTC
ENV SUPERVISOR_PHP_COMMAND="/usr/bin/php -d variables_order=EGPCS /var/www/html/artisan serve --host=0.0.0.0 --port=80"
ENV SUPERVISOR_PHP_USER="sail"

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && mkdir -p /etc/apt/keyrings \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 dnsutils librsvg2-bin fswatch ffmpeg nano  \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y php8.3-cli php8.3-dev \
       php8.3-pgsql php8.3-sqlite3 php8.3-gd \
       php8.3-curl \
       php8.3-imap php8.3-mysql php8.3-mbstring \
       php8.3-xml php8.3-zip php8.3-bcmath php8.3-soap \
       php8.3-intl php8.3-readline \
       php8.3-ldap \
       php8.3-msgpack php8.3-igbinary php8.3-redis \
       php8.3-memcached php8.3-pcov php8.3-imagick php8.3-xdebug \
       && pecl install swoole-5.1.2 \
       && echo "extension=swoole.so" > /etc/php/8.3/cli/conf.d/20-swoole.ini \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get update \
    && apt-get install -y $MYSQL_CLIENT \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.3

RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 sail

COPY ./docker/8.3/start-container /usr/local/bin/start-container
COPY ./docker/8.3/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/8.3/php.ini /etc/php/8.3/cli/conf.d/99-sail.ini
RUN chmod +x /usr/local/bin/start-container

COPY ./docker/minio/create_buckets.sh /etc/minio/create_buckets.sh
RUN chmod +x /etc/minio/create_buckets.sh

EXPOSE 80/tcp

ENTRYPOINT ["start-container"]
