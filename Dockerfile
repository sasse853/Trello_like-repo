# Étape 1 : image PHP avec extensions nécessaires
FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    npm \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier tous les fichiers du projet
COPY . .

# Installer les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Installer les dépendances Node.js et compiler les assets
RUN npm install && npm run build

# Exposer le port PHP-FPM
EXPOSE 9000

# Commande par défaut pour lancer PHP-FPM
CMD ["php-fpm"]
