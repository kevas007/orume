<?php
/**
 * ============================================
 * FICHIER DE CONFIGURATION PRINCIPAL
 * ============================================
 * 
 * Ce fichier centralise toutes les configurations de l'application.
 * Les valeurs sont chargées depuis les variables d'environnement (.env)
 * avec des valeurs par défaut pour le développement.
 * 
 * @package Orüme
 * @version 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ORUME_APP')) {
    die('Accès direct interdit');
}

/**
 * Configuration de la base de données
 * Utilise les variables d'environnement Docker ou les valeurs par défaut
 */
define('DB_CONFIG', [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: '',
    'name' => getenv('DB_NAME') ?: 'orume',
    'charset' => 'utf8mb4'
]);

/**
 * Configuration de l'application
 */
define('APP_CONFIG', [
    'name' => 'Orüme',
    'version' => '1.0.0',
    'env' => getenv('APP_ENV') ?: 'development', // development | production
    'debug' => (getenv('APP_DEBUG') === 'true') ? true : false,
    'timezone' => 'Africa/Lome',
    'locale' => 'fr_FR'
]);

/**
 * Configuration des chemins
 */
define('PATH_CONFIG', [
    'root' => dirname(__DIR__),
    'src' => dirname(__DIR__) . '/src',
    'public' => dirname(__DIR__) . '/public',
    'admin' => dirname(__DIR__) . '/admin',
    'assets' => dirname(__DIR__) . '/assets',
    'uploads' => dirname(__DIR__) . '/admin/images/Admin'
]);

/**
 * Configuration des URLs
 */
define('URL_CONFIG', [
    'base' => getenv('BASE_URL') ?: 'http://localhost:8080',
    'admin' => getenv('ADMIN_URL') ?: 'http://localhost:8081',
    'assets' => getenv('BASE_URL') ?: 'http://localhost:8080' . '/assets'
]);

/**
 * Configuration de sécurité
 */
define('SECURITY_CONFIG', [
    'session_name' => 'ORUME_SESSION',
    'session_lifetime' => 3600, // 1 heure
    'csrf_token_name' => 'csrf_token',
    'password_min_length' => 8
]);

/**
 * Configuration des uploads
 */
define('UPLOAD_CONFIG', [
    'max_size' => 5 * 1024 * 1024, // 5 MB
    'allowed_types' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
    'upload_dir' => PATH_CONFIG['uploads']
]);

// Définir le fuseau horaire
date_default_timezone_set(APP_CONFIG['timezone']);

// Définir la locale
setlocale(LC_ALL, APP_CONFIG['locale']);

// Activer l'affichage des erreurs en développement uniquement
if (APP_CONFIG['env'] === 'development' && APP_CONFIG['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

