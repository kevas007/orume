# 🔌 Configuration des Ports - Orüme

## 📍 Endpoints séparés

Le projet utilise **des ports différents** pour le frontend public et l'admin :

### Frontend Public
- **URL** : `http://localhost:8080/`
- **Port** : `8080`
- **Service Docker** : `orume_web`
- **DocumentRoot** : `/var/www/html/`
- **Configuration** : `docker/apache/orume.conf`

### Admin (Port séparé)
- **URL** : `http://localhost:8081/`
- **Port** : `8081`
- **Service Docker** : `orume_web_admin`
- **DocumentRoot** : `/var/www/html/admin/`
- **Configuration** : `docker/apache/admin.conf`

### phpMyAdmin
- **URL** : `http://localhost:8082/`
- **Port** : `8082`
- **Service Docker** : `orume_phpmyadmin`

### MySQL
- **Port** : `3306`
- **Service Docker** : `orume_db`

## 🔧 Modification des ports

Pour changer les ports, modifiez le fichier `.env` :

```env
WEB_PORT=8080          # Port pour le frontend public
ADMIN_PORT=8081        # Port pour l'admin
DB_PORT=3306           # Port pour MySQL
PHPMYADMIN_PORT=8082   # Port pour phpMyAdmin
```

Puis redémarrez les conteneurs :
```bash
docker-compose down
docker-compose up -d
```

## ⚠️ Important

- Les deux services web (frontend et admin) sont **complètement indépendants**
- L'admin est **bloqué** sur le port 8080 (frontend public)
- Le frontend public ne peut **pas accéder** au dossier admin
- Chaque service a sa propre configuration Apache

## 🚀 Avantages

1. **Séparation complète** : Frontend et admin sur des ports différents
2. **Sécurité** : L'admin n'est pas accessible depuis le frontend
3. **Flexibilité** : Possibilité de déployer sur des serveurs différents
4. **Isolation** : Chaque service peut être redémarré indépendamment

