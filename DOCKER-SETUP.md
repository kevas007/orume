# 🐳 Configuration Docker - Orüme

## ✅ Structure créée

### Fichiers Docker
- ✅ `docker-compose.yml` - Orchestration des services
- ✅ `Dockerfile` - Image PHP 8.2 avec Apache
- ✅ `docker/apache/orume.conf` - Configuration Apache
- ✅ `docker/mysql/init.sql` - Script d'initialisation de la base de données
- ✅ `.dockerignore` - Fichiers à ignorer lors du build

### Configuration
- ✅ `env.example` - Template de configuration
- ✅ `.htaccess` - Configuration Apache pour le frontend public
- ✅ `admin/.htaccess` - Configuration Apache pour l'admin
- ✅ `partials/connect.php` - Mis à jour pour utiliser les variables d'environnement

### Scripts de démarrage
- ✅ `start.bat` - Script Windows
- ✅ `start.sh` - Script Linux/Mac

### Documentation
- ✅ `README-DOCKER.md` - Guide complet d'utilisation

## 📍 Endpoints configurés

### Frontend Public
- **URL** : `http://localhost:8080/`
- **Port** : 8080
- **Racine** : `/var/www/html/`
- **Fichiers** : `acceuil.php`, `portfolio.php`, `contact.php`, etc.
- **Service Docker** : `orume_web`

### Admin (Port séparé)
- **URL** : `http://localhost:8081/`
- **Port** : 8081 (port séparé)
- **Racine** : `/var/www/html/admin/`
- **Fichiers** : `admin/index.php`, `admin/portfolio.php`, etc.
- **Service Docker** : `orume_web_admin`

## 🗄️ Base de données

### Configuration
- **Host** : `db` (nom du service Docker)
- **User** : `orume_user`
- **Password** : `orume_password`
- **Database** : `orume`
- **Port** : `3306` (exposé sur localhost)

### Ports configurés
- **Frontend Public** : `8080` (variable `WEB_PORT`)
- **Admin** : `8081` (variable `ADMIN_PORT`)
- **MySQL** : `3306` (variable `DB_PORT`)
- **phpMyAdmin** : `8082` (variable `PHPMYADMIN_PORT`)

### Tables créées automatiquement
- `users` - Utilisateurs admin
- `messages` - Messages de contact
- `sites` - Sites web du portfolio
- `affiches` - Affiches
- `identites` - Identités visuelles
- `shootings` - Shootings

## 🚀 Démarrage

### Windows
```bash
start.bat
```

### Linux/Mac
```bash
chmod +x start.sh
./start.sh
```

### Manuel
```bash
# Créer le fichier .env
cp env.example .env

# Démarrer les conteneurs
docker-compose up -d
```

## 🔧 Services Docker

1. **web** (orume_web)
   - PHP 8.2 avec Apache
   - Port : 8080 (Frontend public)
   - Volumes : Code source monté

2. **web-admin** (orume_web_admin)
   - PHP 8.2 avec Apache
   - Port : 8081 (Admin - port séparé)
   - Volumes : Code source monté
   - DocumentRoot : `/var/www/html/admin`

3. **db** (orume_db)
   - MySQL 8.0
   - Port : 3306
   - Volume persistant : `db_data`

4. **phpmyadmin** (orume_phpmyadmin)
   - phpMyAdmin
   - Port : 8082
   - Accès : http://localhost:8082

## 📝 Notes importantes

1. **Premier démarrage** : La base de données est initialisée automatiquement avec le script `init.sql`

2. **Utilisateur admin par défaut** :
   - Username : `admin`
   - Email : `admin@orume.com`
   - Password : `admin123` (⚠️ À changer !)

3. **Variables d'environnement** : Modifiez `.env` pour personnaliser la configuration

4. **Volumes** : Les fichiers sont montés en volume, les modifications sont immédiates

5. **Logs** : Utilisez `docker-compose logs -f` pour voir les logs en temps réel

## 🔒 Sécurité

⚠️ **À faire avant la production** :
- Changer tous les mots de passe par défaut
- Ne pas commiter le fichier `.env`
- Implémenter l'authentification admin
- Configurer HTTPS
- Limiter l'accès à phpMyAdmin

