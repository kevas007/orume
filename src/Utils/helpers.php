<?php
/**
 * ============================================
 * FONCTIONS UTILITAIRES GLOBALES
 * ============================================
 * 
 * Ce fichier contient des fonctions utilitaires réutilisables
 * dans toute l'application.
 * 
 * @package Orüme\Utils
 * @version 1.0.0
 */

/**
 * Rediriger vers une URL
 * 
 * @param string $url URL de destination
 * @param int $statusCode Code HTTP de redirection (par défaut 302)
 * @return void
 */
function redirect(string $url, int $statusCode = 302): void
{
    header("Location: {$url}", true, $statusCode);
    exit;
}

/**
 * Échapper une chaîne pour l'affichage HTML
 * 
 * @param string $string Chaîne à échapper
 * @return string Chaîne échappée
 */
function e(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Formater une date
 * 
 * @param string $date Date à formater
 * @param string $format Format de sortie (par défaut 'd/m/Y')
 * @return string Date formatée
 */
function formatDate(string $date, string $format = 'd/m/Y'): string
{
    $timestamp = strtotime($date);
    return $timestamp ? date($format, $timestamp) : $date;
}

/**
 * Générer un token CSRF
 * 
 * @return string Token CSRF
 */
function generateCsrfToken(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifier un token CSRF
 * 
 * @param string $token Token à vérifier
 * @return bool True si le token est valide
 */
function verifyCsrfToken(string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Valider une adresse email
 * 
 * @param string $email Email à valider
 * @return bool True si l'email est valide
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Uploader un fichier
 * 
 * @param array $file Fichier $_FILES
 * @param string $destination Dossier de destination
 * @param array $allowedTypes Types MIME autorisés
 * @return array|false Tableau avec 'path' et 'filename' ou false en cas d'erreur
 */
function uploadFile(array $file, string $destination, array $allowedTypes = [])
{
    // Vérifier les erreurs d'upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // Vérifier le type MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!empty($allowedTypes) && !in_array($mimeType, $allowedTypes)) {
        return false;
    }
    
    // Générer un nom de fichier unique
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $destinationPath = rtrim($destination, '/') . '/' . $filename;
    
    // Créer le dossier s'il n'existe pas
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    // Déplacer le fichier
    if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
        return [
            'path' => $destinationPath,
            'filename' => $filename,
            'original_name' => $file['name'],
            'size' => $file['size'],
            'type' => $mimeType
        ];
    }
    
    return false;
}

/**
 * Obtenir une valeur d'un tableau avec une valeur par défaut
 * 
 * @param array $array Tableau
 * @param string $key Clé
 * @param mixed $default Valeur par défaut
 * @return mixed Valeur ou valeur par défaut
 */
function arrayGet(array $array, string $key, $default = null)
{
    return $array[$key] ?? $default;
}

/**
 * Afficher un message flash
 * 
 * @param string $type Type de message (success, error, warning, info)
 * @param string $message Message à afficher
 * @return void
 */
function setFlashMessage(string $type, string $message): void
{
    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Récupérer et supprimer les messages flash
 * 
 * @return array Messages flash
 */
function getFlashMessages(): array
{
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    return $messages;
}

/**
 * Déboguer une variable (uniquement en mode développement)
 * 
 * @param mixed $var Variable à déboguer
 * @param bool $die Arrêter l'exécution après l'affichage
 * @return void
 */
function dd($var, bool $die = true): void
{
    if (defined('APP_CONFIG') && APP_CONFIG['debug']) {
        echo '<pre style="background: #f4f4f4; padding: 15px; border: 1px solid #ddd; border-radius: 5px; margin: 20px;">';
        var_dump($var);
        echo '</pre>';
        if ($die) {
            die();
        }
    }
}

