# Trello_like

Application de gestion de tâches du genre Trello développée avec Laravel et dockerisée pour un déploiement rapide et uniforme.

## 📋 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- [Docker Desktop](https://www.docker.com/products/docker-desktop) (version 20.10 ou supérieure)
- [Git](https://git-scm.com/downloads)

## 🚀 Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/sasse853/Trello_like-repo.git
cd trello_like
```

### 2. Créer le fichier d'environnement

Copiez le fichier `.env.example` et renommez-le en `.env` :

```bash
copy .env.example .env
```

Le fichier `.env` est déjà configuré pour fonctionner avec Docker et SQLite.

### 3. Lancer les conteneurs Docker

```bash
docker compose up -d --build
```

Cette commande va :
- Construire l'image Docker PHP avec toutes les dépendances nécessaires
- Démarrer les conteneurs Nginx et PHP-FPM
- Installer automatiquement les dépendances Composer

### 4. Générer la clé d'application

```bash
docker compose exec app php artisan key:generate
```

### 5. Exécuter les migrations

```bash
docker compose exec app php artisan migrate
```

### 6. Accéder à l'application

Ouvrez votre navigateur et accédez à :

```
http://localhost:8080
```

## 🛠️ Architecture Docker

L'application utilise Docker Compose avec les services suivants :

- **app** : Conteneur PHP 8.4-FPM qui exécute l'application Laravel
- **nginx** : Serveur web qui gère les requêtes HTTP et communique avec PHP-FPM
- **Base de données** : SQLite (fichier `database/database.sqlite`)

### Structure des fichiers Docker

```
.
├── docker/
│   └── nginx/
│       └── default.conf    # Configuration Nginx
├── Dockerfile              # Image PHP personnalisée
└── docker-compose.yml      # Orchestration des conteneurs
```

## 📦 Commandes utiles

### Gestion des conteneurs

```bash
# Démarrer les conteneurs
docker compose up -d

# Arrêter les conteneurs
docker compose down

# Voir les logs en temps réel
docker compose logs -f

# Voir l'état des conteneurs
docker compose ps

# Redémarrer les conteneurs
docker compose restart
```

### Commandes Laravel (Artisan)

```bash
# Exécuter une commande Artisan
docker compose exec app php artisan <commande>

# Exemples courants
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:list
docker compose exec app php artisan make:controller NomController
docker compose exec app php artisan make:model NomModel -m
```

### Accéder au conteneur

```bash
# Ouvrir un shell dans le conteneur PHP
docker compose exec app bash

# Ouvrir un shell dans le conteneur Nginx
docker compose exec nginx sh
```

### Gestion de Composer

```bash
# Installer une nouvelle dépendance
docker compose exec app composer require nom/package

# Mettre à jour les dépendances
docker compose exec app composer update

# Installer les dépendances
docker compose exec app composer install
```

## 🔧 Configuration

### Variables d'environnement importantes

Dans le fichier `.env` :

```env
APP_NAME=Trello_like
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```

### Changer le port

Si le port 8080 est déjà utilisé, modifiez cette ligne dans `docker-compose.yml` :

```yaml
ports:
  - "NOUVEAU_PORT:80"  # Par exemple "8081:80"
```

Puis redémarrez les conteneurs :

```bash
docker compose down
docker compose up -d
```

## 🐛 Dépannage

### Le port 8080 est déjà utilisé

Changez le port dans `docker-compose.yml` comme indiqué ci-dessus.

### Erreur de permissions sur les fichiers

```bash
docker compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
```

### Les modifications de code ne sont pas prises en compte

Videz les caches :

```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
```

### Réinitialiser complètement l'environnement

```bash
# Arrêter et supprimer tous les conteneurs
docker compose down

# Supprimer l'image construite
docker rmi trello-app

# Reconstruire et redémarrer
docker compose up -d --build
```

## 📝 Développement

### Workflow de développement

1. Modifiez vos fichiers PHP localement (dans `app/`, `routes/`, etc.)
2. Les modifications sont **automatiquement synchronisées** dans le conteneur grâce aux volumes Docker
3. Rafraîchissez votre navigateur pour voir les changements

### Tests

```bash
# Exécuter les tests
docker compose exec app php artisan test

# Exécuter les tests avec couverture
docker compose exec app php artisan test --coverage
```

## 📚 Technologies utilisées

- **Laravel** 11.x - Framework PHP
- **PHP** 8.4 - Langage de programmation
- **Nginx** - Serveur web
- **SQLite** - Base de données
- **Docker** - Conteneurisation
- **Docker Compose** - Orchestration des conteneurs


