-- ============================================
-- BASE DE DONNÉES ORÜME - SCRIPT D'INITIALISATION COMPLET
-- ============================================
-- 
-- Ce script crée la base de données avec toutes les tables
-- et leurs relations (clés étrangères) directement intégrées.
-- 
-- @package Orüme
-- @version 2.0.0

-- Créer la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS orume CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE orume;

-- Désactiver temporairement les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- TABLE DES UTILISATEURS ADMIN
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE DES CLIENTS
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
-- TABLE DES MESSAGES DE CONTACT
-- ============================================
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    sujet VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    statut ENUM('non_lu', 'lu', 'repondu') DEFAULT 'non_lu',
    user_id INT NULL,
    client_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_statut (statut),
    INDEX idx_email (email),
    INDEX idx_user_id (user_id),
    INDEX idx_client_id (client_id),
    CONSTRAINT fk_messages_user 
        FOREIGN KEY (user_id) REFERENCES users(id) 
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_messages_client 
        FOREIGN KEY (client_id) REFERENCES clients(id) 
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE DES SITES WEB (PORTFOLIO)
-- ============================================
CREATE TABLE IF NOT EXISTS sites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    date_realisation DATE NOT NULL,
    contact VARCHAR(255) NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    user_id INT NULL,
    client_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_client_name (client_name),
    INDEX idx_date_realisation (date_realisation),
    INDEX idx_user_id (user_id),
    INDEX idx_client_id (client_id),
    CONSTRAINT fk_sites_user 
        FOREIGN KEY (user_id) REFERENCES users(id) 
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_sites_client 
        FOREIGN KEY (client_id) REFERENCES clients(id) 
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE DES AFFICHES
-- ============================================
CREATE TABLE IF NOT EXISTS affiches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    date_realisation DATE NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    user_id INT NULL,
    client_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_client_name (client_name),
    INDEX idx_date_realisation (date_realisation),
    INDEX idx_user_id (user_id),
    INDEX idx_client_id (client_id),
    CONSTRAINT fk_affiches_user 
        FOREIGN KEY (user_id) REFERENCES users(id) 
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_affiches_client 
        FOREIGN KEY (client_id) REFERENCES clients(id) 
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE DES IDENTITÉS VISUELLES
-- ============================================
CREATE TABLE IF NOT EXISTS identites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    date_realisation DATE NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    user_id INT NULL,
    client_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_client_name (client_name),
    INDEX idx_date_realisation (date_realisation),
    INDEX idx_user_id (user_id),
    INDEX idx_client_id (client_id),
    CONSTRAINT fk_identites_user 
        FOREIGN KEY (user_id) REFERENCES users(id) 
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_identites_client 
        FOREIGN KEY (client_id) REFERENCES clients(id) 
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE DES SHOOTINGS
-- ============================================
CREATE TABLE IF NOT EXISTS shootings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    date_realisation DATE NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    user_id INT NULL,
    client_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_client_name (client_name),
    INDEX idx_date_realisation (date_realisation),
    INDEX idx_user_id (user_id),
    INDEX idx_client_id (client_id),
    CONSTRAINT fk_shootings_user 
        FOREIGN KEY (user_id) REFERENCES users(id) 
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_shootings_client 
        FOREIGN KEY (client_id) REFERENCES clients(id) 
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Réactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- DONNÉES INITIALES
-- ============================================

-- Insérer un utilisateur admin par défaut (mot de passe: admin123)
-- ⚠️ À changer en production !
-- Hash bcrypt pour "admin123"
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@orume.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE username=username;

-- Message de confirmation
SELECT 'Base de données Orüme créée avec succès avec toutes les relations!' AS message;
