# 🐳 Guide Docker - Orüme

Ce guide explique comment utiliser Docker pour déployer le projet Orüme avec séparation des endpoints public et admin.

## 📋 Prérequis

- Docker Desktop installé (Windows/Mac) ou Docker + Docker Compose (Linux)
- Git (optionnel)

## 🚀 Démarrage rapide

### 1. Configuration

Copiez le fichier `.env.example` en `.env` :
```bash
cp env.example .env
```

Modifiez les valeurs dans `.env` si nécessaire (mots de passe, ports, etc.).

### 2. Démarrer les conteneurs

```bash
docker-compose up -d
```

Cette commande va :
- Construire l'image PHP-Apache
- Démarrer MySQL
- Démarrer phpMyAdmin
- Configurer le réseau

### 3. Accéder à l'application

- **Frontend Public** : http://localhost:8080/
- **Admin** : http://localhost:8081/ (port séparé)
- **phpMyAdmin** : http://localhost:8082/

## 📁 Structure des endpoints

### Frontend Public (http://localhost:8080/)
- `/` → `acceuil.php` (page d'accueil)
- `/portfolio.php` → Portfolio public
- `/contact.php` → Formulaire de contact
- `/assets/` → Fichiers statiques (CSS, JS, images)

### Admin (http://localhost:8081/ - Port séparé)
- `/` → `admin/index.php` (dashboard)
- `/portfolio.php` → Gestion sites web
- `/affiche.php` → Gestion affiches
- `/Messages.php` → Gestion messages
- `/identités.php` → Gestion identités visuelles

## 🛠️ Commandes utiles

### Voir les logs
```bash
docker-compose logs -f web
docker-compose logs -f db
```

### Arrêter les conteneurs
```bash
docker-compose down
```

### Arrêter et supprimer les volumes (⚠️ supprime la base de données)
```bash
docker-compose down -v
```

### Reconstruire les images
```bash
docker-compose build --no-cache
```

### Accéder au shell du conteneur web
```bash
docker exec -it orume_web bash
```

### Accéder à MySQL
```bash
docker exec -it orume_db mysql -u orume_user -p
# Mot de passe : orume_password
```

## 🔧 Configuration

### Variables d'environnement

Modifiez `.env` pour changer :
- Ports d'accès :
  - `WEB_PORT=8080` - Frontend public
  - `ADMIN_PORT=8081` - Admin (port séparé)
  - `DB_PORT=3306` - MySQL
  - `PHPMYADMIN_PORT=8082` - phpMyAdmin
- Mots de passe de la base de données
- Noms d'utilisateurs

### Configuration Apache

- `docker/apache/orume.conf` : Configuration pour le frontend public (port 8080)
- `docker/apache/admin.conf` : Configuration pour l'admin (port 8081, port séparé)
- Les deux services sont complètement indépendants

## 🗄️ Base de données

### Initialisation

La base de données est automatiquement créée au premier démarrage avec :
- Tables : `users`, `messages`, `sites`, `affiches`, `identites`, `shootings`
- Utilisateur admin par défaut :
  - Username : `admin`
  - Email : `admin@orume.com`
  - Mot de passe : `admin123` (⚠️ À changer en production !)

### Script SQL

Le script `docker/mysql/init.sql` est exécuté automatiquement au premier démarrage.

## 🔒 Sécurité

⚠️ **Important pour la production** :
1. Changez tous les mots de passe par défaut
2. Ne commitez jamais le fichier `.env`
3. Configurez l'authentification admin
4. Activez HTTPS
5. Limitez l'accès à phpMyAdmin

## 🐛 Dépannage

### Port déjà utilisé
Si le port 8080 est déjà utilisé, modifiez `WEB_PORT` dans `.env` et `docker-compose.yml`.

### Erreur de connexion à la base de données
Vérifiez que le conteneur `db` est démarré :
```bash
docker-compose ps
```

### Permissions de fichiers
Si vous avez des problèmes de permissions :
```bash
docker exec -it orume_web chown -R www-data:www-data /var/www/html
```

## 📝 Notes

- Les fichiers sont montés en volume, les modifications sont immédiates
- La base de données persiste dans le volume `db_data`
- Les logs Apache sont accessibles via `docker-compose logs`

