# PHP application only
FROM php:8.2-cli

ARG imagemagic_config=/etc/ImageMagick-6/policy.xml

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libmagickwand-dev \
    libmagickcore-dev \
    unzip \
    libzip-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    libfreetype6-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo_mysql \
    && docker-php-ext-configure gd \
        --with-jpeg \
        --with-webp \
        --with-freetype \
    && docker-php-ext-install -j$(nproc) gd \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && if [ -f $imagemagic_config ] ; then \
         sed -i 's/<policy domain="coder" rights="none" pattern="PDF" \/>/<policy domain="coder" rights="read|write" pattern="PDF" \/>/g' $imagemagic_config ; \
       else \
         echo "did not see file $imagemagic_config" ; \
       fi \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set proper permissions
RUN chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
RUN chmod -R 775 storage bootstrap/cache 2>/dev/null || true

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
