FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    curl \
    zip \
    unzip \
    git

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy the application code
COPY . .

RUN composer install

EXPOSE 9000

CMD ["php-fpm"]