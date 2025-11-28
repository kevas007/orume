-- ============================================
-- SCRIPT D'AJOUT DE RELATIONS ENTRE LES TABLES
-- ============================================
-- 
-- Ce script ajoute des relations (clés étrangères) entre les tables
-- pour améliorer l'intégrité référentielle de la base de données.
-- 
-- @package Orüme
-- @version 1.0.0

USE orume;

-- Désactiver temporairement les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- CRÉER LA TABLE CLIENTS (si elle n'existe pas)
-- ============================================
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    telephone VARCHAR(50),
    adresse TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nom (nom),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- AJOUTER LES COLONNES DE RELATION
-- ============================================

-- Ajouter user_id aux tables de portfolio (qui a créé le projet)
-- Utiliser des procédures pour éviter les erreurs si les colonnes existent déjà

DELIMITER //

-- Fonction pour ajouter une colonne si elle n'existe pas
DROP PROCEDURE IF EXISTS add_column_if_not_exists//
CREATE PROCEDURE add_column_if_not_exists(
    IN table_name VARCHAR(64),
    IN column_name VARCHAR(64),
    IN column_definition TEXT
)
BEGIN
    DECLARE column_exists INT DEFAULT 0;
    SELECT COUNT(*) INTO column_exists
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = table_name
    AND COLUMN_NAME = column_name;
    
    IF column_exists = 0 THEN
        SET @sql = CONCAT('ALTER TABLE ', table_name, ' ADD COLUMN ', column_name, ' ', column_definition);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END//

-- Ajouter les colonnes user_id et client_id
CALL add_column_if_not_exists('sites', 'user_id', 'INT');
CALL add_column_if_not_exists('sites', 'client_id', 'INT');
CALL add_column_if_not_exists('affiches', 'user_id', 'INT');
CALL add_column_if_not_exists('affiches', 'client_id', 'INT');
CALL add_column_if_not_exists('identites', 'user_id', 'INT');
CALL add_column_if_not_exists('identites', 'client_id', 'INT');
CALL add_column_if_not_exists('shootings', 'user_id', 'INT');
CALL add_column_if_not_exists('shootings', 'client_id', 'INT');
CALL add_column_if_not_exists('messages', 'user_id', 'INT');
CALL add_column_if_not_exists('messages', 'client_id', 'INT');

DROP PROCEDURE IF EXISTS add_column_if_not_exists//

DELIMITER ;

-- ============================================
-- CRÉER LES CLÉS ÉTRANGÈRES
-- ============================================

-- Relations avec la table users
-- Supprimer les clés existantes si elles existent (utiliser des procédures)
DELIMITER //

DROP PROCEDURE IF EXISTS drop_foreign_key_if_exists//
CREATE PROCEDURE drop_foreign_key_if_exists(
    IN table_name VARCHAR(64),
    IN constraint_name VARCHAR(64)
)
BEGIN
    DECLARE constraint_exists INT DEFAULT 0;
    SELECT COUNT(*) INTO constraint_exists
    FROM information_schema.TABLE_CONSTRAINTS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = table_name
    AND CONSTRAINT_NAME = constraint_name
    AND CONSTRAINT_TYPE = 'FOREIGN KEY';
    
    IF constraint_exists > 0 THEN
        SET @sql = CONCAT('ALTER TABLE ', table_name, ' DROP FOREIGN KEY ', constraint_name);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END//

CALL drop_foreign_key_if_exists('sites', 'fk_sites_user');
CALL drop_foreign_key_if_exists('affiches', 'fk_affiches_user');
CALL drop_foreign_key_if_exists('identites', 'fk_identites_user');
CALL drop_foreign_key_if_exists('shootings', 'fk_shootings_user');
CALL drop_foreign_key_if_exists('messages', 'fk_messages_user');

DROP PROCEDURE IF EXISTS drop_foreign_key_if_exists//

DELIMITER ;

-- Ajouter les clés étrangères vers users
ALTER TABLE sites 
ADD CONSTRAINT fk_sites_user 
FOREIGN KEY (user_id) REFERENCES users(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE affiches 
ADD CONSTRAINT fk_affiches_user 
FOREIGN KEY (user_id) REFERENCES users(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE identites 
ADD CONSTRAINT fk_identites_user 
FOREIGN KEY (user_id) REFERENCES users(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE shootings 
ADD CONSTRAINT fk_shootings_user 
FOREIGN KEY (user_id) REFERENCES users(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE messages 
ADD CONSTRAINT fk_messages_user 
FOREIGN KEY (user_id) REFERENCES users(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- Relations avec la table clients
-- Supprimer les clés existantes si elles existent
DELIMITER //

DROP PROCEDURE IF EXISTS drop_foreign_key_if_exists//
CREATE PROCEDURE drop_foreign_key_if_exists(
    IN table_name VARCHAR(64),
    IN constraint_name VARCHAR(64)
)
BEGIN
    DECLARE constraint_exists INT DEFAULT 0;
    SELECT COUNT(*) INTO constraint_exists
    FROM information_schema.TABLE_CONSTRAINTS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = table_name
    AND CONSTRAINT_NAME = constraint_name
    AND CONSTRAINT_TYPE = 'FOREIGN KEY';
    
    IF constraint_exists > 0 THEN
        SET @sql = CONCAT('ALTER TABLE ', table_name, ' DROP FOREIGN KEY ', constraint_name);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END//

CALL drop_foreign_key_if_exists('sites', 'fk_sites_client');
CALL drop_foreign_key_if_exists('affiches', 'fk_affiches_client');
CALL drop_foreign_key_if_exists('identites', 'fk_identites_client');
CALL drop_foreign_key_if_exists('shootings', 'fk_shootings_client');
CALL drop_foreign_key_if_exists('messages', 'fk_messages_client');

DROP PROCEDURE IF EXISTS drop_foreign_key_if_exists//

DELIMITER ;

-- Ajouter les clés étrangères vers clients
ALTER TABLE sites 
ADD CONSTRAINT fk_sites_client 
FOREIGN KEY (client_id) REFERENCES clients(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE affiches 
ADD CONSTRAINT fk_affiches_client 
FOREIGN KEY (client_id) REFERENCES clients(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE identites 
ADD CONSTRAINT fk_identites_client 
FOREIGN KEY (client_id) REFERENCES clients(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE shootings 
ADD CONSTRAINT fk_shootings_client 
FOREIGN KEY (client_id) REFERENCES clients(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE messages 
ADD CONSTRAINT fk_messages_client 
FOREIGN KEY (client_id) REFERENCES clients(id) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- ============================================
-- CRÉER DES INDEX POUR AMÉLIORER LES PERFORMANCES
-- ============================================

DELIMITER //

DROP PROCEDURE IF EXISTS create_index_if_not_exists//
CREATE PROCEDURE create_index_if_not_exists(
    IN table_name VARCHAR(64),
    IN index_name VARCHAR(64),
    IN column_name VARCHAR(64)
)
BEGIN
    DECLARE index_exists INT DEFAULT 0;
    SELECT COUNT(*) INTO index_exists
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = table_name
    AND INDEX_NAME = index_name;
    
    IF index_exists = 0 THEN
        SET @sql = CONCAT('CREATE INDEX ', index_name, ' ON ', table_name, '(', column_name, ')');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END//

CALL create_index_if_not_exists('sites', 'idx_sites_user_id', 'user_id');
CALL create_index_if_not_exists('sites', 'idx_sites_client_id', 'client_id');
CALL create_index_if_not_exists('affiches', 'idx_affiches_user_id', 'user_id');
CALL create_index_if_not_exists('affiches', 'idx_affiches_client_id', 'client_id');
CALL create_index_if_not_exists('identites', 'idx_identites_user_id', 'user_id');
CALL create_index_if_not_exists('identites', 'idx_identites_client_id', 'client_id');
CALL create_index_if_not_exists('shootings', 'idx_shootings_user_id', 'user_id');
CALL create_index_if_not_exists('shootings', 'idx_shootings_client_id', 'client_id');
CALL create_index_if_not_exists('messages', 'idx_messages_user_id', 'user_id');
CALL create_index_if_not_exists('messages', 'idx_messages_client_id', 'client_id');

DROP PROCEDURE IF EXISTS create_index_if_not_exists//

DELIMITER ;

-- Réactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- MIGRATION DES DONNÉES EXISTANTES
-- ============================================
-- Créer des clients à partir des client_name existants

-- Insérer les clients uniques depuis les tables existantes
INSERT INTO clients (nom, email)
SELECT DISTINCT client_name, NULL
FROM (
    SELECT client_name FROM sites
    UNION
    SELECT client_name FROM affiches
    UNION
    SELECT client_name FROM identites
    UNION
    SELECT client_name FROM shootings
) AS all_clients
WHERE NOT EXISTS (
    SELECT 1 FROM clients WHERE clients.nom = all_clients.client_name
);

-- Mettre à jour les client_id dans les tables
UPDATE sites s
INNER JOIN clients c ON s.client_name = c.nom
SET s.client_id = c.id
WHERE s.client_id IS NULL;

UPDATE affiches a
INNER JOIN clients c ON a.client_name = c.nom
SET a.client_id = c.id
WHERE a.client_id IS NULL;

UPDATE identites i
INNER JOIN clients c ON i.client_name = c.nom
SET i.client_id = c.id
WHERE i.client_id IS NULL;

UPDATE shootings sh
INNER JOIN clients c ON sh.client_name = c.nom
SET sh.client_id = c.id
WHERE sh.client_id IS NULL;

-- Mettre à jour les messages avec les clients correspondants
UPDATE messages m
INNER JOIN clients c ON m.nom = c.nom
SET m.client_id = c.id
WHERE m.client_id IS NULL;

-- Mettre à jour les user_id avec l'admin par défaut (si nécessaire)
UPDATE sites SET user_id = 1 WHERE user_id IS NULL;
UPDATE affiches SET user_id = 1 WHERE user_id IS NULL;
UPDATE identites SET user_id = 1 WHERE user_id IS NULL;
UPDATE shootings SET user_id = 1 WHERE user_id IS NULL;

SELECT 'Relations ajoutées avec succès!' AS message;

