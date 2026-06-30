FROM php:8.2-apache

# Install mysqli extension (required for database connection)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy the application source code to the Apache document root
COPY . /var/www/html/

# Expose port 80
EXPOSE 80
