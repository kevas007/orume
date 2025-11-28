<?php
/**
 * ============================================
 * MODÈLE POUR LA GESTION DES SITES WEB
 * ============================================
 * 
 * Ce modèle gère toutes les opérations CRUD liées aux sites web
 * du portfolio (créations de sites pour les clients).
 * 
 * @package Orüme\Models
 * @version 1.0.0
 */

namespace Orume\Models;

use Orume\Core\BaseModel;

class SiteModel extends BaseModel
{
    /**
     * Nom de la table dans la base de données
     * @var string
     */
    protected string $table = 'sites';

    /**
     * Clé primaire de la table
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * Colonnes autorisées pour l'insertion/mise à jour
     * @var array
     */
    protected array $fillable = [
        'client_name',
        'date_realisation',
        'contact',
        'image_path'
    ];

    /**
     * Récupérer les sites récents
     * 
     * @param int $limit Nombre de sites à récupérer
     * @return array Liste des sites
     */
    public function getRecentSites(int $limit = 10): array
    {
        return $this->all('date_realisation', 'DESC', $limit);
    }

    /**
     * Rechercher un site par nom de client
     * 
     * @param string $clientName Nom du client
     * @return array|null Données du site ou null
     */
    public function findByClientName(string $clientName): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE client_name = ? LIMIT 1";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('s', $clientName);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }
}

