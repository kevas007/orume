# 🔗 Relations entre les Tables - Base de Données Orüme

Ce document explique les relations créées entre les tables de la base de données.

## 📊 Structure des Relations

### Table `clients` (Nouvelle)
Table centralisée pour stocker les informations des clients.

**Colonnes :**
- `id` (PK) - Identifiant unique
- `nom` - Nom du client
- `email` - Email du client
- `telephone` - Téléphone du client
- `adresse` - Adresse du client
- `created_at` - Date de création
- `updated_at` - Date de mise à jour

### Relations Créées

#### 1. Relations avec `users` (user_id)
Toutes les tables de portfolio et messages ont maintenant un `user_id` qui référence la table `users`.

**Tables concernées :**
- `sites` → `users.id` (Qui a créé le site web)
- `affiches` → `users.id` (Qui a créé l'affiche)
- `identites` → `users.id` (Qui a créé l'identité visuelle)
- `shootings` → `users.id` (Qui a créé le shooting)
- `messages` → `users.id` (Qui a répondu au message)

**Comportement :**
- `ON DELETE SET NULL` - Si un utilisateur est supprimé, les projets restent mais sans référence
- `ON UPDATE CASCADE` - Si l'ID de l'utilisateur change, les références sont mises à jour

#### 2. Relations avec `clients` (client_id)
Toutes les tables ont maintenant un `client_id` qui référence la table `clients`.

**Tables concernées :**
- `sites` → `clients.id` (Pour quel client)
- `affiches` → `clients.id` (Pour quel client)
- `identites` → `clients.id` (Pour quel client)
- `shootings` → `clients.id` (Pour quel client)
- `messages` → `clients.id` (De quel client)

**Comportement :**
- `ON DELETE SET NULL` - Si un client est supprimé, les projets restent mais sans référence
- `ON UPDATE CASCADE` - Si l'ID du client change, les références sont mises à jour

## 🚀 Utilisation

### Appliquer les Relations

#### Windows (PowerShell)
```powershell
.\add-relations.ps1
```

#### Windows (CMD)
```bash
Get-Content docker\mysql\add-relations.sql | docker exec -i orume_db mysql -u orume_user -porume_password orume
```

#### Linux/Mac
```bash
docker exec -i orume_db mysql -u orume_user -porume_password orume < docker/mysql/add-relations.sql
```

## 📝 Migration Automatique

Le script migre automatiquement les données existantes :

1. **Création de clients** : Extrait les noms de clients uniques depuis les tables existantes
2. **Mise à jour des client_id** : Lie les projets existants aux clients créés
3. **Mise à jour des user_id** : Assigne l'admin par défaut (id=1) aux projets existants

## 🔍 Requêtes Utiles

### Voir tous les projets d'un client
```sql
SELECT s.*, c.nom, c.email 
FROM sites s 
INNER JOIN clients c ON s.client_id = c.id 
WHERE c.id = 1;
```

### Voir tous les projets créés par un utilisateur
```sql
SELECT s.*, u.username 
FROM sites s 
INNER JOIN users u ON s.user_id = u.id 
WHERE u.id = 1;
```

### Voir tous les messages d'un client
```sql
SELECT m.*, c.nom, c.email 
FROM messages m 
INNER JOIN clients c ON m.client_id = c.id 
WHERE c.id = 1;
```

### Compter les projets par client
```sql
SELECT c.nom, 
       COUNT(DISTINCT s.id) as sites,
       COUNT(DISTINCT a.id) as affiches,
       COUNT(DISTINCT i.id) as identites,
       COUNT(DISTINCT sh.id) as shootings
FROM clients c
LEFT JOIN sites s ON c.id = s.client_id
LEFT JOIN affiches a ON c.id = a.client_id
LEFT JOIN identites i ON c.id = i.client_id
LEFT JOIN shootings sh ON c.id = sh.client_id
GROUP BY c.id, c.nom;
```

## ⚠️ Notes Importantes

1. **Données existantes** : Le script migre automatiquement les données existantes
2. **Valeurs NULL** : Les colonnes `user_id` et `client_id` peuvent être NULL (pas obligatoires)
3. **Intégrité référentielle** : Les clés étrangères garantissent que seuls des IDs valides peuvent être utilisés
4. **Performance** : Des index ont été créés sur toutes les colonnes de relation pour améliorer les performances

## 📁 Fichiers

- `docker/mysql/add-relations.sql` - Script SQL principal
- `add-relations.ps1` - Script PowerShell pour Windows
- `RELATIONS-README.md` - Ce fichier de documentation

