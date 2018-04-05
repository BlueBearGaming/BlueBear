FROM bluebeargaming/ubuntu-server

USER root

RUN add-apt-repository ppa:ondrej/php
RUN echo 'fuck you docker cache'
RUN apt-get update

# Custom php extensions
RUN apt-get install -y \
    mariadb-client \
    nginx \
    php5.6-fpm \
    php5.6-cli \
    php5.6-intl \
    php5.6-xml \
    php5.6-zip \
    php5.6-mbstring \
    php5.6-gd \
    php5.6-apcu \
    php5.6-mysql \
    php5.6-curl

# Fix fastcgi params in nginx
COPY nginx/snippets/fastcgi-php.conf /etc/nginx/snippets/fastcgi-php.conf

RUN update-alternatives --set php /usr/bin/php5.6

# Enabling our custom config
COPY php/mods-available/symfony.ini /etc/php/5.6/mods-available/symfony.ini
RUN phpenmod -v 5.6 symfony

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# DEV ONLY !!!
RUN apt-get install -y php-xdebug
COPY php/mods-available/xdebug.ini /etc/php/5.6/mods-available/xdebug.ini

COPY start.sh /var/www/start.sh

# Rollback default shell
USER www-data

ENTRYPOINT ["/var/www/start.sh"]
