# 📁 Structure du Projet Orüme - Refactorisé

## 🎯 Vue d'ensemble

Le projet a été réorganisé selon une architecture MVC (Model-View-Controller) avec séparation claire des responsabilités.

## 📂 Structure des dossiers

```
orume/
├── 📁 admin/                    # Interface d'administration
│   ├── adminpartials/           # Partials admin (head, aside, etc.)
│   ├── css/                     # Styles CSS admin
│   ├── js/                      # Scripts JavaScript admin
│   ├── images/                  # Images uploadées par l'admin
│   ├── index.php                # Dashboard admin
│   ├── portfolio.php           # Gestion sites web
│   ├── affiche.php             # Gestion affiches
│   ├── Messages.php            # Gestion messages
│   └── identités.php           # Gestion identités visuelles
│
├── 📁 assets/                   # Assets frontend public
│   ├── css/                     # Styles CSS public
│   ├── js/                      # Scripts JavaScript public
│   └── img/                     # Images statiques
│
├── 📁 config/                    # Configuration
│   └── config.php               # Configuration principale
│
├── 📁 docker/                    # Configuration Docker
│   ├── apache/                  # Configurations Apache
│   └── mysql/                   # Scripts SQL
│
├── 📁 partials/                  # Partials frontend public
│   ├── head.php                 # En-tête HTML
│   ├── footer.php               # Pied de page
│   └── connect.php              # Connexion DB (déprécié)
│
├── 📁 public/                    # Fichiers publics (à utiliser)
│   ├── admin/                   # Admin accessible publiquement
│   └── assets/                  # Assets publics
│
├── 📁 src/                       # Code source de l'application
│   ├── 📁 Controllers/          # Contrôleurs MVC
│   │   └── ContactController.php
│   │
│   ├── 📁 Core/                 # Classes de base
│   │   ├── App.php              # Classe principale
│   │   ├── Autoloader.php       # Autoloader PSR-4
│   │   ├── BaseModel.php        # Modèle de base (CRUD)
│   │   └── Database.php         # Gestion BDD (Singleton)
│   │
│   ├── 📁 Models/               # Modèles de données
│   │   ├── MessageModel.php     # Modèle messages
│   │   ├── SiteModel.php        # Modèle sites web
│   │   ├── AfficheModel.php     # Modèle affiches
│   │   └── UserModel.php        # Modèle utilisateurs
│   │
│   └── 📁 Utils/                 # Utilitaires
│       └── helpers.php          # Fonctions utilitaires
│
├── 📄 bootstrap.php              # Fichier d'amorçage
├── 📄 acceuil.php               # Page d'accueil
├── 📄 contact.php               # Page de contact
├── 📄 portfolio.php             # Page portfolio public
├── 📄 docker-compose.yml        # Configuration Docker
├── 📄 Dockerfile                # Image Docker frontend
├── 📄 Dockerfile.admin          # Image Docker admin
└── 📄 .gitignore                # Fichiers ignorés par Git
```

## 🏗️ Architecture MVC

### Models (Modèles)
Les modèles héritent de `BaseModel` et gèrent les opérations CRUD sur les tables de la base de données.

**Exemple :**
```php
use Orüme\Models\MessageModel;

$messageModel = new MessageModel();
$messages = $messageModel->all(); // Récupérer tous les messages
```

### Controllers (Contrôleurs)
Les contrôleurs gèrent la logique métier et coordonnent les modèles et les vues.

**Exemple :**
```php
use Orüme\Controllers\ContactController;

$controller = new ContactController();
$controller->submit(); // Traiter le formulaire
```

### Views (Vues)
Les vues sont les fichiers PHP qui contiennent le HTML. Elles sont dans les dossiers racine pour le frontend et dans `admin/` pour l'admin.

## 🔧 Utilisation

### Initialiser l'application

```php
// Au début de chaque fichier PHP
require_once __DIR__ . '/bootstrap.php';
```

### Utiliser un modèle

```php
use Orüme\Models\MessageModel;

$messageModel = new MessageModel();
$unreadCount = $messageModel->countUnread();
```

### Utiliser la base de données

```php
use Orüme\Core\Database;

$db = Database::getInstance();
$result = $db->query("SELECT * FROM messages");
```

### Utiliser les fonctions utilitaires

```php
// Échapper du HTML
echo e($userInput);

// Rediriger
redirect('/admin/index.php');

// Valider un email
if (isValidEmail($email)) {
    // ...
}
```

## 📝 Commentaires dans le code

Tous les fichiers ont été commentés avec :
- **En-tête de fichier** : Description du fichier, package, version
- **Commentaires de classe** : Description de la classe et de ses responsabilités
- **Commentaires de méthode** : Description, paramètres, valeur de retour
- **Commentaires inline** : Explications des parties complexes

## 🔒 Sécurité

- ✅ Prepared statements pour toutes les requêtes SQL
- ✅ Échappement HTML avec `e()`
- ✅ Validation des entrées utilisateur
- ✅ Tokens CSRF pour les formulaires
- ✅ Hashage des mots de passe avec `password_hash()`

## 🚀 Améliorations futures

1. **Routing** : Système de routing pour des URLs propres
2. **Middleware** : Système de middleware pour l'authentification
3. **API REST** : Endpoints API pour les opérations AJAX
4. **Validation** : Classe de validation centralisée
5. **Templates** : Système de templates pour les vues

