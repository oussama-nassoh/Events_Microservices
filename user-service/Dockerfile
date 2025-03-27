FROM php:8.2-fpm


WORKDIR /var/www/html


RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*


RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u 1000 -d /home/laravel laravel
RUN mkdir -p /home/laravel/.composer && \
    chown -R laravel:laravel /home/laravel


RUN chown -R laravel:laravel /var/www/html


COPY --chown=laravel:laravel . .

RUN if [ -f .env.example ]; then \
    cp .env.example .env; \
    sed -i 's/DB_HOST=.*/DB_HOST=user-db/g' .env; \
    sed -i 's/DB_PORT=.*/DB_PORT=3306/g' .env; \
    sed -i 's/DB_DATABASE=.*/DB_DATABASE=user_service_db/g' .env; \
    sed -i 's/DB_USERNAME=.*/DB_USERNAME=laravel/g' .env; \
    sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=password/g' .env; \
    fi


RUN composer install --no-interaction --no-scripts

RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER laravel

EXPOSE 8002


COPY --chown=laravel:laravel docker-entrypoint.sh /var/www/html/docker-entrypoint.sh
RUN chmod +x /var/www/html/docker-entrypoint.sh

CMD ["/bin/bash", "-c", "/var/www/html/docker-entrypoint.sh"]