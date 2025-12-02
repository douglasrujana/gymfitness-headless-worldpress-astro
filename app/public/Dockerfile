# Usar imagen oficial de WordPress con PHP-Apache
FROM wordpress:6.4-apache

# Instalar extensiones PHP y herramientas necesarias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    wget \
    && docker-php-ext-install mysqli \
    && rm -rf /var/lib/apt/lists/*

# Habilitar módulos de Apache necesarios
RUN a2enmod rewrite \
    && a2enmod ssl \
    && a2enmod headers

# Copiar wp-config.php - lee variables de entorno de Railway
COPY wp-config.php /var/www/html/wp-config.php

# Copiar wp-content personalizado
COPY wp-content/ /var/www/html/wp-content/

# NO copiar .env - Solo debe existir en local para desarrollo
# Las variables de entorno vendrán de Railway

# Establecer permisos correctos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod 644 /var/www/html/wp-config.php

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

EXPOSE 80 443

CMD ["apache2-foreground"]
