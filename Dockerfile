# Use the official PHP image as a base image
FROM php:8.0-apache

# Copy application files to the container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (if needed for pretty URLs)
RUN a2enmod rewrite
