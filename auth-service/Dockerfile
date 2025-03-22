FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
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

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/laravel laravel
RUN mkdir -p /home/laravel/.composer && \
    chown -R laravel:laravel /home/laravel

# Set proper permissions
RUN chown -R laravel:laravel /var/www/html

# Copy application files
COPY --chown=laravel:laravel . .

# Create proper .env file from .env.example
RUN if [ -f .env.example ]; then \
    cp .env.example .env; \
    sed -i 's/DB_HOST=.*/DB_HOST=auth-db/g' .env; \
    sed -i 's/DB_PORT=.*/DB_PORT=3306/g' .env; \
    sed -i 's/DB_DATABASE=.*/DB_DATABASE=auth_service_db/g' .env; \
    sed -i 's/DB_USERNAME=.*/DB_USERNAME=laravel/g' .env; \
    sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=password/g' .env; \
    fi

# Install composer dependencies
RUN composer install --no-interaction --no-scripts

# Set up proper permissions
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER laravel

EXPOSE 8000

# Run artisan commands
RUN php artisan key:generate
RUN php artisan config:cache

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8001"]