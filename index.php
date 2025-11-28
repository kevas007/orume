<?php
/**
 * ============================================
 * FICHIER D'INDEX - REDIRECTION VERS ACCUEIL
 * ============================================
 * 
 * Ce fichier redirige automatiquement vers acceuil.php
 * lorsque l'utilisateur accède à la racine du site.
 * 
 * @package Orüme
 * @version 1.0.0
 */

// Redirection permanente vers acceuil.php
header("Location: acceuil.php", true, 301);
exit;

