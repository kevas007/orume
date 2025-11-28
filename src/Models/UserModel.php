<?php
/**
 * ============================================
 * MODÈLE POUR LA GESTION DES UTILISATEURS
 * ============================================
 * 
 * Ce modèle gère toutes les opérations liées aux utilisateurs
 * administrateurs du système.
 * 
 * @package Orüme\Models
 * @version 1.0.0
 */

namespace Orume\Models;

use Orume\Core\BaseModel;

class UserModel extends BaseModel
{
    /**
     * Nom de la table dans la base de données
     * @var string
     */
    protected string $table = 'users';

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
        'username',
        'email',
        'password'
    ];

    /**
     * Trouver un utilisateur par son email
     * 
     * @param string $email Email de l'utilisateur
     * @return array|null Données de l'utilisateur ou null
     */
    public function findByEmail(string $email): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    /**
     * Trouver un utilisateur par son nom d'utilisateur
     * 
     * @param string $username Nom d'utilisateur
     * @return array|null Données de l'utilisateur ou null
     */
    public function findByUsername(string $username): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE username = ? LIMIT 1";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    /**
     * Vérifier les identifiants de connexion
     * 
     * @param string $email Email ou nom d'utilisateur
     * @param string $password Mot de passe en clair
     * @return array|false Données de l'utilisateur ou false
     */
    public function authenticate(string $email, string $password)
    {
        // Chercher par email ou username
        $user = $this->findByEmail($email) ?: $this->findByUsername($email);

        if (!$user) {
            return false;
        }

        // Vérifier le mot de passe
        if (password_verify($password, $user['password'])) {
            // Ne pas retourner le mot de passe
            unset($user['password']);
            return $user;
        }

        return false;
    }

    /**
     * Créer un nouvel utilisateur avec mot de passe hashé
     * 
     * @param array $data Données de l'utilisateur
     * @return int|false ID du nouvel utilisateur ou false
     */
    public function create(array $data)
    {
        // Hasher le mot de passe si présent
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return parent::create($data);
    }

    /**
     * Mettre à jour le mot de passe d'un utilisateur
     * 
     * @param int $id ID de l'utilisateur
     * @param string $newPassword Nouveau mot de passe en clair
     * @return bool Succès de l'opération
     */
    public function updatePassword(int $id, string $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($id, ['password' => $hashedPassword]);
    }
}

