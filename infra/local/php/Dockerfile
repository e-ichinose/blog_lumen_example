FROM php:8.1-fpm

# install packages
RUN apt-get update \
  && apt-get install -y \
  git \
  libpq-dev \
  curl \
  openssl \
  && docker-php-ext-install pdo_pgsql pgsql

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install stable node and latest npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
  && apt-get install -y nodejs \
  && npm install -g n \
  && n stable \
  && npm update -g npm

# copy files and directories
COPY . /var/www

# set working directory
WORKDIR /var/www

# set file permissions
RUN chown -R www-data:www-data /var/www

# Add php.ini
COPY php.ini /usr/local/etc/php/
