FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_pgsql pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy all files
COPY . /app

# Create storage and bootstrap/cache directories
RUN mkdir -p /app/storage /app/bootstrap/cache

# Set permissions
RUN chmod -R 777 /app/storage /app/bootstrap/cache

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Run post-install scripts
RUN composer run-script post-autoload-dump --no-interaction

# Install Node dependencies and build assets
RUN npm install && npm run build

# Set final permissions
RUN chmod -R 777 /app/storage /app/bootstrap/cache

# Expose port 10000
EXPOSE 10000

# Start command
CMD php artisan serve --host=0.0.0.0 --port=10000