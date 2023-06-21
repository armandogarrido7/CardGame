FROM php:8.1-fpm

RUN apt-get update -y && apt-get upgrade -y

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN apt-get install libpq-dev -y

RUN docker-php-ext-install pdo_pgsql && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

COPY php.ini /usr/local/etc/php/conf.d/

RUN apt-get install zip -y && \
    apt-get install git -y && \
    apt-get install yarn -y && \
    apt-get install bash -y && \
    apt-get install nano -y

RUN curl -fsSL https://deb.nodesource.com/setup_14.x |  bash -  && \
    apt-get install -y nodejs

# Yarn
RUN apt-get update && apt-get install -yq \
    apt-transport-https && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    apt-get update && apt-get install -yq \
    yarn

ARG UID=1000
ARG GID=1000

RUN usermod www-data -u ${UID} \
    && groupmod -g ${GID} www-data

RUN chown www-data:www-data /var/www