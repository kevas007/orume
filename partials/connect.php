<?php
/**
 * ============================================
 * FICHIER DE CONNEXION À LA BASE DE DONNÉES
 * ============================================
 * 
 * ⚠️ DÉPRÉCIÉ : Ce fichier est conservé pour la compatibilité
 * avec l'ancien code. Utilisez plutôt la classe Database via
 * le système d'autoloading.
 * 
 * Pour utiliser la nouvelle structure :
 * require_once __DIR__ . '/../../bootstrap.php';
 * $db = \Orüme\Core\Database::getInstance();
 * $connect = $db->getConnection();
 * 
 * @package Orüme
 * @deprecated Utiliser Database::getInstance() à la place
 * @version 1.0.0
 */

// Utiliser les variables d'environnement Docker ou les valeurs par défaut
$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'orume_user';
$pass = getenv('DB_PASS') ?: 'orume_password';
$dbname = getenv('DB_NAME') ?: 'orume';

// Connexion
$connect = @mysqli_connect($host, $user, $pass, $dbname);

// Vérification
if ($connect) {
    mysqli_set_charset($connect, "utf8mb4");
} else {
    // En production, logger l'erreur au lieu de l'afficher
    error_log("Échec de connexion à la base de données : " . mysqli_connect_error());
    $connect = null;
}
