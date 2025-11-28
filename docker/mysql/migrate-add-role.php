<?php
/**
 * ============================================
 * MIGRATION : Ajouter la colonne 'role' à la table users
 * ============================================
 * 
 * Ce script ajoute la colonne 'role' à la table users si elle n'existe pas déjà
 * 
 * @package Orüme
 * @version 1.0.0
 */

// Utiliser les variables d'environnement Docker ou les valeurs par défaut
$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'orume_user';
$pass = getenv('DB_PASS') ?: 'orume_password';
$dbname = getenv('DB_NAME') ?: 'orume';

// Connexion
$connect = @mysqli_connect($host, $user, $pass, $dbname);

if (!$connect) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error() . "\n");
}

mysqli_set_charset($connect, "utf8mb4");

echo "Connexion à la base de données réussie.\n";

// Vérifier si la colonne 'role' existe déjà
$checkColumn = "SELECT COUNT(*) as count 
                FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = '$dbname' 
                AND TABLE_NAME = 'users' 
                AND COLUMN_NAME = 'role'";

$result = mysqli_query($connect, $checkColumn);
$row = mysqli_fetch_assoc($result);
$columnExists = $row['count'] > 0;

if ($columnExists) {
    echo "La colonne 'role' existe déjà dans la table 'users'.\n";
} else {
    echo "Ajout de la colonne 'role' à la table 'users'...\n";
    
    // Ajouter la colonne 'role'
    $alterTable = "ALTER TABLE users 
                   ADD COLUMN role ENUM('admin', 'user') DEFAULT 'user' AFTER password";
    
    if (mysqli_query($connect, $alterTable)) {
        echo "Colonne 'role' ajoutée avec succès.\n";
        
        // Ajouter l'index sur role
        $addIndex = "ALTER TABLE users ADD INDEX idx_role (role)";
        if (mysqli_query($connect, $addIndex)) {
            echo "Index sur 'role' ajouté avec succès.\n";
        }
        
        // Mettre à jour le premier utilisateur existant en 'admin'
        $updateAdmin = "UPDATE users SET role = 'admin' WHERE id = (SELECT MIN(id) FROM (SELECT id FROM users) AS temp) LIMIT 1";
        if (mysqli_query($connect, $updateAdmin)) {
            echo "Premier utilisateur mis à jour avec le rôle 'admin'.\n";
        }
        
        // Mettre à jour tous les autres utilisateurs en 'user' s'ils n'ont pas de rôle
        $updateUsers = "UPDATE users SET role = 'user' WHERE role IS NULL OR role = ''";
        if (mysqli_query($connect, $updateUsers)) {
            echo "Autres utilisateurs mis à jour avec le rôle 'user'.\n";
        }
        
    } else {
        echo "Erreur lors de l'ajout de la colonne 'role' : " . mysqli_error($connect) . "\n";
    }
}

mysqli_close($connect);
echo "Migration terminée.\n";

