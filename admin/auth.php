<?php
/**
 * ============================================
 * FICHIER D'AUTHENTIFICATION
 * ============================================
 * 
 * Ce fichier doit être inclus au début de toutes les pages admin
 * pour vérifier que l'utilisateur est connecté.
 * 
 * @package Orüme\Admin
 * @version 1.0.0
 */

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    // Rediriger vers la page de connexion
    header('Location: login.php');
    exit;
}

// Fonction pour vérifier si l'utilisateur est admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fonction pour obtenir l'ID de l'utilisateur connecté
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Fonction pour obtenir le nom d'utilisateur
function getUsername() {
    return $_SESSION['username'] ?? null;
}

// Fonction pour obtenir le rôle
function getUserRole() {
    return $_SESSION['role'] ?? null;
}

