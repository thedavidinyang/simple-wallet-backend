FROM php:8.2.4

# Install system dependencies
RUN apt-get update -y && apt-get install -y \
    unzip \
    libpq-dev \
    libcurl4-gnutls-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    zlib1g-dev \
    libfreetype6-dev \
    libxpm-dev \
    libgd-dev \
    libzip-dev \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    default-mysql-client \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-xpm --with-freetype \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql  pdo_pgsql mysqli bcmath zip mbstring exif pcntl

# # Install Redis extension
# RUN pecl install redis && docker-php-ext-enable redis

# Configure PHP
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "upload_max_filesize=50M" >> /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "post_max_size=50M" >> /usr/local/etc/php/conf.d/memory-limit.ini

# Set working directory
WORKDIR /var/www

# Copy application code
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Set directory permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# RUN apt-get update && apt-get install -y supervisor
# RUN mkdir -p /var/log/supervisor
# RUN mkdir -p /var/run/supervisor


# COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf


# Expose port 4000
EXPOSE 5000 4000 9000

# Copy and set permissions for the start script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Set entrypoint
ENTRYPOINT ["/bin/bash", "-c", "/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf & /usr/local/bin/start.sh"]