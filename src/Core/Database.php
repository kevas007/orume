<?php
/**
 * ============================================
 * CLASSE DE GESTION DE LA BASE DE DONNÉES
 * ============================================
 * 
 * Cette classe gère la connexion à la base de données MySQL
 * en utilisant MySQLi avec un pattern Singleton.
 * 
 * @package Orüme\Core
 * @version 1.0.0
 */

namespace Orume\Core;

use mysqli;
use mysqli_sql_exception;

class Database
{
    /**
     * Instance unique de la classe (Singleton)
     * @var Database|null
     */
    private static ?Database $instance = null;

    /**
     * Connexion MySQLi
     * @var mysqli|null
     */
    private ?mysqli $connection = null;

    /**
     * Configuration de la base de données
     * @var array
     */
    private array $config;

    /**
     * Constructeur privé pour empêcher l'instanciation directe
     * 
     * @param array $config Configuration de la base de données
     */
    private function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }

    /**
     * Obtenir l'instance unique de la classe (Singleton)
     * 
     * @param array|null $config Configuration optionnelle
     * @return Database Instance de la classe
     */
    public static function getInstance(?array $config = null): Database
    {
        if (self::$instance === null) {
            // Utiliser la configuration par défaut si non fournie
            if ($config === null) {
                require_once dirname(__DIR__, 2) . '/config/config.php';
                $config = DB_CONFIG;
            }
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * Établir la connexion à la base de données
     * 
     * @return void
     * @throws mysqli_sql_exception Si la connexion échoue
     */
    private function connect(): void
    {
        try {
            $this->connection = new mysqli(
                $this->config['host'],
                $this->config['user'],
                $this->config['pass'],
                $this->config['name']
            );

            // Vérifier les erreurs de connexion
            if ($this->connection->connect_error) {
                throw new mysqli_sql_exception(
                    "Erreur de connexion : " . $this->connection->connect_error
                );
            }

            // Définir le charset
            $charset = $this->config['charset'] ?? 'utf8mb4';
            $this->connection->set_charset($charset);

        } catch (mysqli_sql_exception $e) {
            // Logger l'erreur en production
            error_log("Erreur de connexion à la base de données : " . $e->getMessage());
            
            // Afficher l'erreur uniquement en développement
            if (defined('APP_CONFIG') && APP_CONFIG['debug']) {
                die("Erreur de connexion : " . $e->getMessage());
            } else {
                die("Erreur de connexion à la base de données. Veuillez contacter l'administrateur.");
            }
        }
    }

    /**
     * Obtenir la connexion MySQLi
     * 
     * @return mysqli Connexion MySQLi
     */
    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    /**
     * Exécuter une requête SQL
     * 
     * @param string $query Requête SQL
     * @return \mysqli_result|bool Résultat de la requête
     */
    public function query(string $query)
    {
        $result = $this->connection->query($query);
        
        if (!$result) {
            error_log("Erreur SQL : " . $this->connection->error);
            if (defined('APP_CONFIG') && APP_CONFIG['debug']) {
                throw new mysqli_sql_exception("Erreur SQL : " . $this->connection->error);
            }
        }
        
        return $result;
    }

    /**
     * Préparer une requête SQL (prepared statement)
     * 
     * @param string $query Requête SQL avec placeholders
     * @return \mysqli_stmt|false Statement préparé
     */
    public function prepare(string $query)
    {
        return $this->connection->prepare($query);
    }

    /**
     * Échapper une chaîne pour éviter les injections SQL
     * 
     * @param string $string Chaîne à échapper
     * @return string Chaîne échappée
     */
    public function escape(string $string): string
    {
        return $this->connection->real_escape_string($string);
    }

    /**
     * Obtenir le dernier ID inséré
     * 
     * @return int|string Dernier ID inséré
     */
    public function lastInsertId()
    {
        return $this->connection->insert_id;
    }

    /**
     * Obtenir le nombre de lignes affectées
     * 
     * @return int Nombre de lignes affectées
     */
    public function affectedRows(): int
    {
        return $this->connection->affected_rows;
    }

    /**
     * Démarrer une transaction
     * 
     * @return bool Succès de l'opération
     */
    public function beginTransaction(): bool
    {
        return $this->connection->begin_transaction();
    }

    /**
     * Valider une transaction
     * 
     * @return bool Succès de l'opération
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * Annuler une transaction
     * 
     * @return bool Succès de l'opération
     */
    public function rollback(): bool
    {
        return $this->connection->rollback();
    }

    /**
     * Fermer la connexion
     * 
     * @return void
     */
    public function close(): void
    {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
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

