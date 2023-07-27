FROM php:8.2-fpm
ARG TIMEZONE

RUN echo 'alias sf="php bin/console"' >> ~/.bashrc

# Set php.ini
RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY php.override.ini $PHP_INI_DIR/conf.d/99-overrides.ini

COPY www.conf $PHP_INI_DIR/conf.d/99-overrides.ini


# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone
RUN printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


RUN apt-get update && apt-get -y dist-upgrade;
RUN apt-get update && apt-get install -y
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        openssl \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libfreetype6-dev \
        libpng-dev \
        libwebp-dev \
        mariadb-client

RUN docker-php-ext-install zip intl pdo pdo_mysql mysqli

# Install xdebug
RUN pecl install xdebug apcu \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-enable \
    		apcu \
    		opcache


# Entrypoint
ADD entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh


#ENTRYPOINT ["/entrypoint.sh"]
