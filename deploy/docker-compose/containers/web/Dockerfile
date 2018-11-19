FROM quay.io/sitewards/apache-php:7.2-latest

ENV USER_UID 1000
ENV DEBIAN_FRONTEND noninteractive

# Install PHP required extensions
RUN apt-get update && \
    apt-get install --yes \
        php-sqlite3 \
        php7.2-xdebug

# Configure XDBEUG
ADD etc/php/7.2/mods-available/xdebug.ini /etc/php/7.2/mods-available/xdebug.ini

RUN for PHP_CONFIG_PATH in "/etc/php/7.2/cli/" "/etc/php/7.2/apache2/"; do \
        sed --in-place 's/;opcache.enable=1/opcache.enable=0/' "${PHP_CONFIG_PATH}/php.ini" && \
        sed --in-place 's/opcache.enable_cli=1/opcache.enable_cli=0/' "${PHP_CONFIG_PATH}/php.ini"; \
    done;

# Modify apache to run as a specific user; should be the user who owns the PHP files being worked on.
# Last in the stack as it invalidates cache.
RUN adduser \
        --uid $USER_UID \
        --gecos "" \
        --disabled-password developer && \
  sed --in-place 's/www-data/developer/' /etc/apache2/envvars