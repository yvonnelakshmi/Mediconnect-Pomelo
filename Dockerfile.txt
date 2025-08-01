# Use the official PHP image
FROM php:8.1-apache

# Change Apache to listen on port 8080 (Cloud Run requirement)
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf && \
    sed -i 's/80/8080/g' /etc/apache2/sites-enabled/000-default.conf

# Copy your application code to Apache's root folder
COPY . /var/www/html/

# Set proper file permissions
RUN chown -R www-data:www-data /var/www/html

# Enable mod_rewrite (if needed)
RUN a2enmod rewrite

# Expose the port Cloud Run expects
EXPOSE 8080

# Start Apache in the foreground
CMD ["apache2-foreground"]
