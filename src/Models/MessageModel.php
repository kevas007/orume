<?php
/**
 * ============================================
 * MODÈLE POUR LA GESTION DES MESSAGES
 * ============================================
 * 
 * Ce modèle gère toutes les opérations CRUD liées aux messages
 * de contact reçus depuis le formulaire public.
 * 
 * @package Orüme\Models
 * @version 1.0.0
 */

namespace Orume\Models;

use Orume\Core\BaseModel;

class MessageModel extends BaseModel
{
    /**
     * Nom de la table dans la base de données
     * @var string
     */
    protected string $table = 'messages';

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
        'nom',
        'email',
        'sujet',
        'message',
        'statut'
    ];

    /**
     * Récupérer les messages non lus
     * 
     * @return array Liste des messages non lus
     */
    public function getUnreadMessages(): array
    {
        $query = "SELECT * FROM {$this->table} WHERE statut = 'non_lu' ORDER BY created_at DESC";
        $result = $this->db->query($query);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Marquer un message comme lu
     * 
     * @param int $id ID du message
     * @return bool Succès de l'opération
     */
    public function markAsRead(int $id): bool
    {
        return $this->update($id, ['statut' => 'lu']);
    }

    /**
     * Marquer un message comme répondu
     * 
     * @param int $id ID du message
     * @return bool Succès de l'opération
     */
    public function markAsReplied(int $id): bool
    {
        return $this->update($id, ['statut' => 'repondu']);
    }

    /**
     * Compter les messages non lus
     * 
     * @return int Nombre de messages non lus
     */
    public function countUnread(): int
    {
        return $this->count("statut = 'non_lu'");
    }

    /**
     * Compter tous les messages
     * 
     * @return int Nombre total de messages
     */
    public function countAll(): int
    {
        return $this->count();
    }
}

