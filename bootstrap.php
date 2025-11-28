<?php
/**
 * ============================================
 * FICHIER D'AMORÇAGE DE L'APPLICATION
 * ============================================
 * 
 * Ce fichier doit être inclus au début de tous les fichiers PHP
 * pour initialiser l'application, charger l'autoloader et
 * la configuration.
 * 
 * @package Orüme
 * @version 1.0.0
 */

// Charger l'application
require_once __DIR__ . '/src/Core/App.php';

use Orüme\Core\App;

// Initialiser l'application
App::getInstance();

