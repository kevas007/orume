-- ============================================
-- MIGRATION : Ajouter la colonne 'role' à la table users
-- ============================================
-- 
-- Ce script ajoute la colonne 'role' à la table users si elle n'existe pas déjà
-- et met à jour les utilisateurs existants avec le rôle 'admin' par défaut
-- 
-- @package Orüme
-- @version 1.0.0

USE orume;

DELIMITER //

-- Procédure pour ajouter la colonne role si elle n'existe pas
DROP PROCEDURE IF EXISTS add_role_column_if_not_exists//
CREATE PROCEDURE add_role_column_if_not_exists()
BEGIN
    DECLARE column_exists INT DEFAULT 0;
    SELECT COUNT(*) INTO column_exists
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'users'
    AND COLUMN_NAME = 'role';
    
    IF column_exists = 0 THEN
        ALTER TABLE users 
        ADD COLUMN role ENUM('admin', 'user') DEFAULT 'user' AFTER password;
        
        ALTER TABLE users 
        ADD INDEX idx_role (role);
        
        -- Mettre à jour le premier utilisateur existant en 'admin'
        UPDATE users 
        SET role = 'admin' 
        WHERE id = (SELECT MIN(id) FROM (SELECT id FROM users) AS temp)
        LIMIT 1;
        
        -- Mettre à jour tous les autres utilisateurs en 'user' s'ils n'ont pas de rôle
        UPDATE users 
        SET role = 'user' 
        WHERE role IS NULL OR role = '';
        
        SELECT 'Colonne role ajoutée avec succès à la table users!' AS message;
    ELSE
        SELECT 'La colonne role existe déjà dans la table users.' AS message;
    END IF;
END//

CALL add_role_column_if_not_exists()//
DROP PROCEDURE IF EXISTS add_role_column_if_not_exists//

DELIMITER ;

