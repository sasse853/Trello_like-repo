FROM php:8.4-cli

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Installer les extensions PHP nécessaires pour SQLite
RUN apt-get install -y libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers nécessaires à Composer
COPY composer.json composer.lock ./

# Créer le .env à partir du .env.example pour le build
COPY .env.example .env

# Installer les dépendances Laravel
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# Copier le reste des fichiers du projet
COPY . .

# Créer le fichier SQLite
RUN mkdir -p database && touch database/database.sqlite

# Générer la clé d'application
RUN php artisan key:generate

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Démarrer les migrations puis le serveur, en écoutant sur le port fourni par Render
CMD php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}