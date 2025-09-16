FROM php:8.2-cli

# Install system dependencies: git, unzip, curl, and zip extension
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev curl \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Default command keeps container running for interactive dev (override as needed)
CMD ["tail", "-f", "/dev/null"]

