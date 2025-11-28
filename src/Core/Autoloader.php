<?php
/**
 * ============================================
 * AUTOLOADER PSR-4
 * ============================================
 * 
 * Cette classe gère le chargement automatique des classes
 * selon le standard PSR-4.
 * 
 * @package Orüme\Core
 * @version 1.0.0
 */

namespace Orüme\Core;

class Autoloader
{
    /**
     * Enregistrer l'autoloader
     * 
     * @return void
     */
    public static function register(): void
    {
        spl_autoload_register([self::class, 'load']);
    }

    /**
     * Charger une classe
     * 
     * @param string $class Nom complet de la classe (avec namespace)
     * @return void
     */
    public static function load(string $class): void
    {
        // Convertir le namespace en chemin de fichier
        $prefix = 'Orüme\\';
        $baseDir = dirname(__DIR__) . '/';

        // Vérifier si la classe appartient au namespace Orüme
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }

        // Récupérer le nom de classe relatif
        $relativeClass = substr($class, $len);

        // Convertir le namespace en chemin de fichier
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        // Charger le fichier s'il existe
        if (file_exists($file)) {
            require_once $file;
        }
    }
}

