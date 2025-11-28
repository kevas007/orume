<?php
/**
 * ============================================
 * MODÈLE POUR LA GESTION DES AFFICHES
 * ============================================
 * 
 * Ce modèle gère toutes les opérations CRUD liées aux affiches
 * créées pour les clients (design graphique).
 * 
 * @package Orüme\Models
 * @version 1.0.0
 */

namespace Orume\Models;

use Orume\Core\BaseModel;

class AfficheModel extends BaseModel
{
    /**
     * Nom de la table dans la base de données
     * @var string
     */
    protected string $table = 'affiches';

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
        'image_path'
    ];

    /**
     * Récupérer les affiches récentes
     * 
     * @param int $limit Nombre d'affiches à récupérer
     * @return array Liste des affiches
     */
    public function getRecentAffiches(int $limit = 10): array
    {
        return $this->all('date_realisation', 'DESC', $limit);
    }
}

