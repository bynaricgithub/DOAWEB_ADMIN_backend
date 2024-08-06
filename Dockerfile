FROM existenz/webstack:8.2

# Install required packages and dependencies
RUN apk update
RUN apk add --no-cache \
    php82-openssl \
    php82-session \
    php82 \
    php82-posix \
    php82-tokenizer \
    php82-pdo \
    php82-pdo_mysql \
    php82-zip \
    php82-xml \
    php82-simplexml \
    php82-fileinfo \
    php82-xmlreader \
    php82-xmlwriter \
    php82-iconv \
    php82-mbstring \
    php82-curl \
    php82-gd \
    php82-ftp \
    libstdc++ \
    libpq \
    php82-pecl-swoole \
    php82-pcntl \
    php82-phar \
    php82-bcmath \
    php82-sodium \
    supervisor \
    git \
    curl

# Copy your application code into the container
COPY --chown=php:nginx . /www

# Copy configuration files
COPY .docker/php.ini /etc/php82/php.ini
COPY .docker/vhost.conf /etc/nginx/nginx.conf
COPY .docker/supervisord.ini /etc/supervisor.d/supervisord.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php82 -- --install-dir=/usr/local/bin --filename=composer

# Change working directory to the application root
WORKDIR /www

# Install application dependencies using Composer
RUN php82 /usr/local/bin/composer install --no-interaction --no-dev --optimize-autoloader

# Generate optimized files
RUN php82 artisan config:clear
RUN php82 artisan view:clear
RUN php82 artisan optimize
# RUN php82 artisan passport:keys

#RUN php82 artisan down

# Set the user to root for the CMD
USER root

# Start supervisord
CMD ["supervisord", "-c", "/etc/supervisor.d/supervisord.ini"]