# 🌱 Script de Seed - 170 Éléments de Test

Ce script génère **170 éléments de données de test** dans la base de données pour le projet Orüme.

## 📊 Répartition des données

- **20 messages** de contact
- **50 sites web** du portfolio
- **50 affiches** créées
- **50 identités visuelles**
- **50 shootings** produits

**Total : 170 éléments**

## 🚀 Utilisation

### Méthode 1 : Script automatique (Recommandé)

#### Windows
```bash
seed-database.bat
```

#### Linux/Mac
```bash
chmod +x seed-database.sh
./seed-database.sh
```

### Méthode 2 : Commande Docker manuelle

#### Windows (CMD)
```bash
docker exec -i orume_db mysql -u orume_user -porume_password orume < docker\mysql\seed.sql
```

#### Windows (PowerShell)
```powershell
Get-Content docker\mysql\seed.sql | docker exec -i orume_db mysql -u orume_user -porume_password orume
```

#### Linux/Mac
```bash
docker exec -i orume_db mysql -u orume_user -porume_password orume < docker/mysql/seed.sql
```

### Méthode 3 : Via phpMyAdmin

1. Accéder à http://localhost:8082
2. Sélectionner la base de données `orume`
3. Aller dans l'onglet "Importer"
4. Choisir le fichier `docker/mysql/seed.sql`
5. Cliquer sur "Exécuter"

## ⚠️ Important

- **Les tables existantes seront vidées** avant l'insertion des nouvelles données
- Les données de test sont **simples**, sans fonctionnalités complexes
- Les dates sont réparties sur les 12 derniers mois
- Les chemins d'images pointent vers les dossiers existants

## 📝 Contenu des données

### Messages
- Noms variés (français et africains)
- Emails de test
- Sujets divers (devis, collaboration, design, etc.)
- Statuts variés (non_lu, lu, repondu)

### Sites Web
- Noms de clients variés
- Dates de réalisation sur 12 mois
- Contacts email
- Chemins d'images vers les fichiers existants

### Affiches
- Clients variés
- Dates réparties
- Chemins vers les images d'affiches existantes

### Identités Visuelles
- 50 identités de marque
- Chemins vers logos (à créer si nécessaire)

### Shootings
- 50 shootings produits
- Chemins vers images de shooting (à créer si nécessaire)

## 🔄 Réinitialiser les données

Pour réinitialiser et recharger les données :

```bash
# Windows
seed-database.bat

# Linux/Mac
./seed-database.sh
```

Le script vide automatiquement les tables avant d'insérer les nouvelles données.

## ✅ Vérification

Après le chargement, vous pouvez vérifier le nombre d'éléments :

```sql
SELECT 
    (SELECT COUNT(*) FROM messages) as messages,
    (SELECT COUNT(*) FROM sites) as sites,
    (SELECT COUNT(*) FROM affiches) as affiches,
    (SELECT COUNT(*) FROM identites) as identites,
    (SELECT COUNT(*) FROM shootings) as shootings;
```

Ou via phpMyAdmin dans l'onglet "SQL".

## 📁 Fichiers

- `docker/mysql/seed.sql` - Script SQL principal
- `seed-database.sh` - Script shell Linux/Mac
- `seed-database.bat` - Script batch Windows
- `seed-database.ps1` - Script PowerShell Windows
- `SEED-README.md` - Ce fichier de documentation

nice