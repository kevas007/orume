<?php
/**
 * ============================================
 * PAGE DE DÉCONNEXION
 * ============================================
 * 
 * Déconnecte l'utilisateur et le redirige vers la page de connexion.
 * 
 * @package Orüme\Admin
 * @version 1.0.0
 */

// Démarrer la session
session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Si on veut détruire complètement la session, supprimer aussi le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion
header('Location: login.php');
exit;

