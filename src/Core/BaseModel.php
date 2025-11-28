<?php
/**
 * ============================================
 * CLASSE DE BASE POUR LES MODÈLES
 * ============================================
 * 
 * Cette classe abstraite fournit les méthodes de base
 * pour tous les modèles de l'application (CRUD).
 * 
 * @package Orüme\Core
 * @version 1.0.0
 */

namespace Orume\Core;

abstract class BaseModel
{
    /**
     * Instance de la base de données
     * @var Database
     */
    protected Database $db;

    /**
     * Nom de la table
     * @var string
     */
    protected string $table;

    /**
     * Clé primaire de la table
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * Colonnes de la table
     * @var array
     */
    protected array $fillable = [];

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Trouver un enregistrement par son ID
     * 
     * @param int $id ID de l'enregistrement
     * @return array|null Données de l'enregistrement ou null
     */
    public function find(int $id): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            return null;
        }
        
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc() ?: null;
    }

    /**
     * Récupérer tous les enregistrements
     * 
     * @param string $orderBy Colonne de tri
     * @param string $order Direction du tri (ASC/DESC)
     * @param int|null $limit Nombre maximum d'enregistrements
     * @return array Liste des enregistrements
     */
    public function all(string $orderBy = 'id', string $order = 'DESC', ?int $limit = null): array
    {
        $query = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}";
        
        if ($limit !== null) {
            $query .= " LIMIT " . (int)$limit;
        }
        
        $result = $this->db->query($query);
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }

    /**
     * Créer un nouvel enregistrement
     * 
     * @param array $data Données à insérer
     * @return int|false ID du nouvel enregistrement ou false en cas d'erreur
     */
    public function create(array $data)
    {
        // Filtrer les données selon les colonnes fillable
        $data = $this->filterFillable($data);
        
        if (empty($data)) {
            return false;
        }
        
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $values = array_values($data);
        
        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $types = str_repeat('s', count($values)); // Tous les types en string par défaut
        $stmt->bind_param($types, ...$values);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Mettre à jour un enregistrement
     * 
     * @param int $id ID de l'enregistrement
     * @param array $data Données à mettre à jour
     * @return bool Succès de l'opération
     */
    public function update(int $id, array $data): bool
    {
        // Filtrer les données selon les colonnes fillable
        $data = $this->filterFillable($data);
        
        if (empty($data)) {
            return false;
        }
        
        $set = [];
        $values = [];
        
        foreach ($data as $column => $value) {
            $set[] = "{$column} = ?";
            $values[] = $value;
        }
        
        $values[] = $id; // Pour le WHERE
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }

    /**
     * Supprimer un enregistrement
     * 
     * @param int $id ID de l'enregistrement
     * @return bool Succès de l'opération
     */
    public function delete(int $id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Filtrer les données selon les colonnes fillable
     * 
     * @param array $data Données à filtrer
     * @return array Données filtrées
     */
    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Compter le nombre d'enregistrements
     * 
     * @param string|null $where Condition WHERE optionnelle
     * @return int Nombre d'enregistrements
     */
    public function count(?string $where = null): int
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if ($where) {
            $query .= " WHERE {$where}";
        }
        
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        
        return (int)$row['total'];
    }
}

