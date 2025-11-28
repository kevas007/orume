<?php
/**
 * ============================================
 * CLASSE PRINCIPALE DE L'APPLICATION
 * ============================================
 * 
 * Cette classe initialise l'application, charge la configuration
 * et gère les sessions.
 * 
 * @package Orüme\Core
 * @version 1.0.0
 */

namespace Orüme\Core;

class App
{
    /**
     * Instance unique de l'application (Singleton)
     * @var App|null
     */
    private static ?App $instance = null;

    /**
     * Constructeur privé pour empêcher l'instanciation directe
     */
    private function __construct()
    {
        $this->init();
    }

    /**
     * Obtenir l'instance unique de l'application
     * 
     * @return App Instance de l'application
     */
    public static function getInstance(): App
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialiser l'application
     * 
     * @return void
     */
    private function init(): void
    {
        // Définir la constante pour autoriser l'accès
        if (!defined('ORUME_APP')) {
            define('ORUME_APP', true);
        }

        // Charger la configuration
        $configPath = dirname(__DIR__, 2) . '/config/config.php';
        if (file_exists($configPath)) {
            require_once $configPath;
        } else {
            // Mode dégradé si le fichier de config n'existe pas
            error_log("Fichier de configuration introuvable : " . $configPath);
            return;
        }

        // Enregistrer l'autoloader
        try {
            Autoloader::register();
        } catch (\Exception $e) {
            error_log("Erreur lors de l'enregistrement de l'autoloader : " . $e->getMessage());
        }

        // Démarrer la session
        try {
            $this->startSession();
        } catch (\Exception $e) {
            error_log("Erreur lors du démarrage de la session : " . $e->getMessage());
        }

        // Charger les fonctions utilitaires
        $helpersPath = dirname(__DIR__) . '/Utils/helpers.php';
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }
    }

    /**
     * Démarrer la session PHP
     * 
     * @return void
     */
    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Vérifier que les constantes sont définies
            if (!defined('SECURITY_CONFIG') || !defined('APP_CONFIG')) {
                // Mode dégradé : session basique
                session_start();
                return;
            }
            
            // Configuration de la session
            $sessionConfig = SECURITY_CONFIG;
            
            session_name($sessionConfig['session_name']);
            session_set_cookie_params([
                'lifetime' => $sessionConfig['session_lifetime'],
                'path' => '/',
                'domain' => '',
                'secure' => APP_CONFIG['env'] === 'production', // HTTPS en production
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            session_start();
        }
    }

    /**
     * Empêcher le clonage de l'instance
     */
    private function __clone() {}

    /**
     * Empêcher la désérialisation
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}

