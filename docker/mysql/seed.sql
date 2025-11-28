-- ============================================
-- SCRIPT DE SEED - 200 ÉLÉMENTS DE TEST
-- ============================================
-- 
-- Ce script génère 200 éléments de données de test
-- pour les différentes tables du projet Orüme.
-- Il ne s'exécute QUE si les tables sont vides.
-- 
-- @package Orüme
-- @version 1.0.0

USE orume;

-- Désactiver les vérifications de clés étrangères temporairement
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- VÉRIFIER SI LES TABLES SONT VIDES
-- ============================================
-- Créer une procédure stockée pour insérer seulement si vide

DELIMITER //

CREATE PROCEDURE IF NOT EXISTS seed_if_empty()
BEGIN
    DECLARE msg_count INT DEFAULT 0;
    DECLARE sites_count INT DEFAULT 0;
    DECLARE affiches_count INT DEFAULT 0;
    DECLARE identites_count INT DEFAULT 0;
    DECLARE shootings_count INT DEFAULT 0;
    
    -- Compter les enregistrements existants
    SELECT COUNT(*) INTO msg_count FROM messages;
    SELECT COUNT(*) INTO sites_count FROM sites;
    SELECT COUNT(*) INTO affiches_count FROM affiches;
    SELECT COUNT(*) INTO identites_count FROM identites;
    SELECT COUNT(*) INTO shootings_count FROM shootings;
    
    -- Insérer les messages seulement si la table est vide (20 messages)
    IF msg_count = 0 THEN
        INSERT INTO messages (nom, email, sujet, message, statut, created_at) VALUES
        ('Jean Dupont', 'jean.dupont@example.com', 'Demande de devis', 'Bonjour, j\'aimerais avoir un devis pour la création d\'un site vitrine.', 'non_lu', NOW() - INTERVAL 1 DAY),
        ('Marie Togo', 'marie.togo@gmail.com', 'Collaboration possible', 'Bonjour, je souhaite collaborer avec votre agence sur un projet web.', 'lu', NOW() - INTERVAL 2 DAY),
        ('Koffi Sena', 'koffi.sena@outlook.com', 'Demande identité visuelle', 'Salut, j\'aimerais confier la création de mon logo à Orüme.', 'non_lu', NOW() - INTERVAL 3 DAY),
        ('Sophie Martin', 'sophie.martin@example.com', 'Demande de site e-commerce', 'Bonjour, j\'ai besoin d\'un site e-commerce pour ma boutique.', 'repondu', NOW() - INTERVAL 4 DAY),
        ('Amadou Diallo', 'amadou.diallo@example.com', 'Refonte de site', 'Je souhaite refaire mon site web existant.', 'non_lu', NOW() - INTERVAL 5 DAY),
        ('Fatou Bamba', 'fatou.bamba@example.com', 'Design graphique', 'Besoin de créations graphiques pour ma campagne publicitaire.', 'lu', NOW() - INTERVAL 6 DAY),
        ('Pierre Legrand', 'pierre.legrand@example.com', 'Shooting produit', 'Je recherche un photographe pour un shooting produit.', 'non_lu', NOW() - INTERVAL 7 DAY),
        ('Aissatou Diallo', 'aissatou.diallo@example.com', 'Logo entreprise', 'Création d\'un logo pour ma nouvelle entreprise.', 'lu', NOW() - INTERVAL 8 DAY),
        ('Thomas Bernard', 'thomas.bernard@example.com', 'Application mobile', 'Développement d\'une application mobile.', 'non_lu', NOW() - INTERVAL 9 DAY),
        ('Aminata Traoré', 'aminata.traore@example.com', 'Site vitrine', 'Création d\'un site vitrine pour mon restaurant.', 'repondu', NOW() - INTERVAL 10 DAY),
        ('David Moreau', 'david.moreau@example.com', 'Affiches publicitaires', 'Besoin d\'affiches pour un événement.', 'non_lu', NOW() - INTERVAL 11 DAY),
        ('Mariam Coulibaly', 'mariam.coulibaly@example.com', 'Identité de marque', 'Création complète d\'une identité de marque.', 'lu', NOW() - INTERVAL 12 DAY),
        ('Nicolas Petit', 'nicolas.petit@example.com', 'Refonte graphique', 'Refonte complète de l\'identité visuelle.', 'non_lu', NOW() - INTERVAL 13 DAY),
        ('Kadiatou Diallo', 'kadiatou.diallo@example.com', 'Packaging design', 'Design de packaging pour mes produits.', 'lu', NOW() - INTERVAL 14 DAY),
        ('Julien Rousseau', 'julien.rousseau@example.com', 'Site portfolio', 'Création d\'un site portfolio pour mes créations.', 'non_lu', NOW() - INTERVAL 15 DAY),
        ('Awa Sarr', 'awa.sarr@example.com', 'Flyers événement', 'Création de flyers pour un événement.', 'repondu', NOW() - INTERVAL 16 DAY),
        ('Marc Dubois', 'marc.dubois@example.com', 'Bannières web', 'Création de bannières publicitaires web.', 'non_lu', NOW() - INTERVAL 17 DAY),
        ('Rokhaya Diop', 'rokhaya.diop@example.com', 'Charte graphique', 'Élaboration d\'une charte graphique complète.', 'lu', NOW() - INTERVAL 18 DAY),
        ('Antoine Leroy', 'antoine.leroy@example.com', 'Site institutionnel', 'Création d\'un site institutionnel.', 'non_lu', NOW() - INTERVAL 19 DAY),
        ('Ndeye Fall', 'ndeye.fall@example.com', 'Photographie produit', 'Shooting photo pour catalogue produits.', 'lu', NOW() - INTERVAL 20 DAY);
    END IF;  

    -- Table des messages (formulaire de contact)
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    sujet VARCHAR(255),
    message TEXT NOT NULL,
    statut ENUM('non_lu', 'lu', 'repondu') DEFAULT 'non_lu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    
    -- Insérer les sites seulement si la table est vide
    IF sites_count = 0 THEN
        INSERT INTO sites (client_name, date_realisation, contact, image_path, created_at) VALUES
        ('Alpha Group', '2024-03-01', 'alpha@gmail.com', 'images/Admin/sites/agri.jpeg', NOW() - INTERVAL 1 DAY),
        ('Bella Studio', '2024-06-01', 'contact@bellastudio.fr', 'images/Admin/sites/furniture.jpeg', NOW() - INTERVAL 2 DAY),
        ('GreenMarket', '2025-01-01', 'GreenMarket@gmail.com', 'images/Admin/sites/grenade.jpeg', NOW() - INTERVAL 3 DAY),
        ('DigitalFood', '2025-04-01', 'info@digitalfood.com', 'images/Admin/sites/raisin.jpeg', NOW() - INTERVAL 4 DAY),
        ('TechCorp', '2024-02-01', 'contact@techcorp.com', 'images/Admin/sites/tech.jpeg', NOW() - INTERVAL 5 DAY),
        ('Luxury Brand', '2024-05-01', 'info@luxurybrand.com', 'images/Admin/sites/luxury.jpeg', NOW() - INTERVAL 6 DAY),
        ('Nature Store', '2024-07-01', 'contact@naturestore.com', 'images/Admin/sites/nature.jpeg', NOW() - INTERVAL 7 DAY),
        ('Business Pro', '2024-08-01', 'info@businesspro.com', 'images/Admin/sites/business.jpeg', NOW() - INTERVAL 8 DAY),
        ('E-Learning Plus', '2024-09-01', 'contact@elearning.com', 'images/Admin/sites/elearning.jpeg', NOW() - INTERVAL 9 DAY),
        ('House Beauty', '2024-10-01', 'info@housebeauty.com', 'images/Admin/sites/housebeauty.jpeg', NOW() - INTERVAL 10 DAY),
        ('Farmer Market', '2024-11-01', 'contact@farmermarket.com', 'images/Admin/sites/farmer.jpeg', NOW() - INTERVAL 11 DAY),
        ('WebDesign Food', '2024-12-01', 'info@webdesignfood.com', 'images/Admin/sites/WebDesign Food.jpeg', NOW() - INTERVAL 12 DAY),
        ('Meuble Design', '2025-02-01', 'contact@meubledesign.com', 'images/Admin/sites/meuble.jpeg', NOW() - INTERVAL 13 DAY),
        ('Startup Tech', '2024-01-15', 'info@startuptech.com', 'images/Admin/sites/tech.jpeg', NOW() - INTERVAL 14 DAY),
        ('Eco Store', '2024-03-15', 'contact@ecostore.com', 'images/Admin/sites/nature.jpeg', NOW() - INTERVAL 15 DAY),
        ('Fashion Hub', '2024-04-15', 'info@fashionhub.com', 'images/Admin/sites/luxury.jpeg', NOW() - INTERVAL 16 DAY),
        ('Food Delivery', '2024-05-15', 'contact@fooddelivery.com', 'images/Admin/sites/WebDesign Food.jpeg', NOW() - INTERVAL 17 DAY),
        ('Real Estate Pro', '2024-06-15', 'info@realestate.com', 'images/Admin/sites/business.jpeg', NOW() - INTERVAL 18 DAY),
        ('Health Care', '2024-07-15', 'contact@healthcare.com', 'images/Admin/sites/elearning.jpeg', NOW() - INTERVAL 19 DAY),
        ('Beauty Salon', '2024-08-15', 'info@beautysalon.com', 'images/Admin/sites/housebeauty.jpeg', NOW() - INTERVAL 20 DAY),
        ('Restaurant Le Gourmet', '2024-09-15', 'contact@legourmet.com', 'images/Admin/sites/WebDesign Food.jpeg', NOW() - INTERVAL 21 DAY),
        ('Tech Solutions', '2024-10-15', 'info@techsolutions.com', 'images/Admin/sites/tech.jpeg', NOW() - INTERVAL 22 DAY),
        ('Green Energy', '2024-11-15', 'contact@greenenergy.com', 'images/Admin/sites/nature.jpeg', NOW() - INTERVAL 23 DAY),
        ('Luxury Hotels', '2024-12-15', 'info@luxuryhotels.com', 'images/Admin/sites/luxury.jpeg', NOW() - INTERVAL 24 DAY),
        ('Edu Platform', '2025-01-15', 'contact@eduplatform.com', 'images/Admin/sites/elearning.jpeg', NOW() - INTERVAL 25 DAY),
        ('Furniture Plus', '2025-02-15', 'info@furnitureplus.com', 'images/Admin/sites/furniture.jpeg', NOW() - INTERVAL 26 DAY),
        ('Agri Tech', '2025-03-15', 'contact@agritech.com', 'images/Admin/sites/agri.jpeg', NOW() - INTERVAL 27 DAY),
        ('Business Hub', '2025-04-15', 'info@businesshub.com', 'images/Admin/sites/business.jpeg', NOW() - INTERVAL 28 DAY),
        ('Food Market', '2025-05-15', 'contact@foodmarket.com', 'images/Admin/sites/grenade.jpeg', NOW() - INTERVAL 29 DAY),
        ('Design Studio', '2025-06-15', 'info@designstudio.com', 'images/Admin/sites/meuble.jpeg', NOW() - INTERVAL 30 DAY),
        ('Tech Innovation', '2024-02-20', 'contact@techinnovation.com', 'images/Admin/sites/tech.jpeg', NOW() - INTERVAL 31 DAY),
        ('Nature Shop', '2024-03-20', 'info@natureshop.com', 'images/Admin/sites/nature.jpeg', NOW() - INTERVAL 32 DAY),
        ('Luxury Boutique', '2024-04-20', 'contact@luxuryboutique.com', 'images/Admin/sites/luxury.jpeg', NOW() - INTERVAL 33 DAY),
        ('E-Learning Academy', '2024-05-20', 'info@elearningacademy.com', 'images/Admin/sites/elearning.jpeg', NOW() - INTERVAL 34 DAY),
        ('Beauty Center', '2024-06-20', 'contact@beautycenter.com', 'images/Admin/sites/housebeauty.jpeg', NOW() - INTERVAL 35 DAY),
        ('Farm Fresh', '2024-07-20', 'info@farmfresh.com', 'images/Admin/sites/farmer.jpeg', NOW() - INTERVAL 36 DAY),
        ('Food Express', '2024-08-20', 'contact@foodexpress.com', 'images/Admin/sites/WebDesign Food.jpeg', NOW() - INTERVAL 37 DAY),
        ('Business Center', '2024-09-20', 'info@businesscenter.com', 'images/Admin/sites/business.jpeg', NOW() - INTERVAL 38 DAY),
        ('Tech Startup', '2024-10-20', 'contact@techstartup.com', 'images/Admin/sites/tech.jpeg', NOW() - INTERVAL 39 DAY),
        ('Eco Market', '2024-11-20', 'info@ecomarket.com', 'images/Admin/sites/nature.jpeg', NOW() - INTERVAL 40 DAY),
        ('Luxury Collection', '2024-12-20', 'contact@luxurycollection.com', 'images/Admin/sites/luxury.jpeg', NOW() - INTERVAL 41 DAY),
        ('Edu Online', '2025-01-20', 'info@eduonline.com', 'images/Admin/sites/elearning.jpeg', NOW() - INTERVAL 42 DAY),
        ('Furniture World', '2025-02-20', 'contact@furnitureworld.com', 'images/Admin/sites/furniture.jpeg', NOW() - INTERVAL 43 DAY),
        ('Agri Solutions', '2025-03-20', 'info@agrisolutions.com', 'images/Admin/sites/agri.jpeg', NOW() - INTERVAL 44 DAY),
        ('Business Pro Plus', '2025-04-20', 'contact@businessproplus.com', 'images/Admin/sites/business.jpeg', NOW() - INTERVAL 45 DAY),
        ('Food Network', '2025-05-20', 'info@foodnetwork.com', 'images/Admin/sites/grenade.jpeg', NOW() - INTERVAL 46 DAY),
        ('Design Lab', '2025-06-20', 'contact@designlab.com', 'images/Admin/sites/meuble.jpeg', NOW() - INTERVAL 47 DAY),
        ('Tech World', '2024-01-25', 'info@techworld.com', 'images/Admin/sites/tech.jpeg', NOW() - INTERVAL 48 DAY),
        ('Nature Plus', '2024-02-25', 'contact@natureplus.com', 'images/Admin/sites/nature.jpeg', NOW() - INTERVAL 49 DAY),
        ('Luxury Line', '2024-03-25', 'info@luxuryline.com', 'images/Admin/sites/luxury.jpeg', NOW() - INTERVAL 50 DAY);
    END IF;
    
    -- Insérer les affiches seulement si la table est vide
    IF affiches_count = 0 THEN
        INSERT INTO affiches (client_name, date_realisation, image_path, created_at) VALUES
        ('Agrocore', '2024-02-01', 'images/Admin/affiches/Agrocore.jpeg', NOW() - INTERVAL 1 DAY),
        ('Skincare Brand', '2024-03-01', 'images/Admin/affiches/SkincareBrand.jpeg', NOW() - INTERVAL 2 DAY),
        ('Café Aroma', '2024-04-01', 'images/Admin/affiches/Coffee shop drink.jpeg', NOW() - INTERVAL 3 DAY),
        ('Studio Vision', '2024-05-01', 'images/Admin/affiches/StudioFlyer.jpeg', NOW() - INTERVAL 4 DAY),
        ('Urban Grill', '2024-06-01', 'images/Admin/affiches/urban grill.jpeg', NOW() - INTERVAL 5 DAY),
        ('Decus', '2024-07-01', 'images/Admin/affiches/Affiche6.jpg', NOW() - INTERVAL 6 DAY),
        ('Zest Market', '2024-08-01', 'images/Admin/affiches/zestmarket.jpeg', NOW() - INTERVAL 7 DAY),
        ('Coffee Iced', '2024-09-01', 'images/Admin/affiches/Coffee Iced Coffee.jpeg', NOW() - INTERVAL 8 DAY),
        ('Grand Launch', '2024-10-01', 'images/Admin/affiches/GrandLaunch.jpeg', NOW() - INTERVAL 9 DAY),
        ('Food Flyer', '2024-11-01', 'images/Admin/affiches/food flyer design_.jpeg', NOW() - INTERVAL 10 DAY),
        ('Minimal Corporate', '2024-12-01', 'images/Admin/affiches/Minimal Corporate Flyer Design PSD Template - PSD Zone.jpeg', NOW() - INTERVAL 11 DAY),
        ('Ice Coffee Shop', '2025-01-01', 'images/Admin/affiches/Ice Coffee.jpeg', NOW() - INTERVAL 12 DAY),
        ('Design for Starbucks', '2025-02-01', 'images/Admin/sites/Design for Starbucks_.jpeg', NOW() - INTERVAL 13 DAY),
        ('Affiche BTP', '2024-01-15', 'images/Admin/affiches/AficheBtp2.jpg', NOW() - INTERVAL 14 DAY),
        ('Affiche 1', '2024-02-15', 'images/Admin/affiches/afiche1.jpg', NOW() - INTERVAL 15 DAY),
        ('Affiche 5', '2024-03-15', 'images/Admin/affiches/Affiche5.jpg', NOW() - INTERVAL 16 DAY),
        ('Affiche 7', '2024-04-15', 'images/Admin/affiches/Affiche7.jpg', NOW() - INTERVAL 17 DAY),
        ('FLIER', '2024-05-15', 'images/Admin/affiches/FLIER.jpg', NOW() - INTERVAL 18 DAY),
        ('Beauty Brand', '2024-06-15', 'images/Admin/affiches/SkincareBrand.jpeg', NOW() - INTERVAL 19 DAY),
        ('Coffee Shop', '2024-07-15', 'images/Admin/affiches/Coffee shop drink.jpeg', NOW() - INTERVAL 20 DAY),
        ('Studio Event', '2024-08-15', 'images/Admin/affiches/StudioFlyer.jpeg', NOW() - INTERVAL 21 DAY),
        ('Grill Restaurant', '2024-09-15', 'images/Admin/affiches/urban grill.jpeg', NOW() - INTERVAL 22 DAY),
        ('Market Place', '2024-10-15', 'images/Admin/affiches/zestmarket.jpeg', NOW() - INTERVAL 23 DAY),
        ('Iced Coffee', '2024-11-15', 'images/Admin/affiches/Coffee Iced Coffee.jpeg', NOW() - INTERVAL 24 DAY),
        ('Launch Event', '2024-12-15', 'images/Admin/affiches/GrandLaunch.jpeg', NOW() - INTERVAL 25 DAY),
        ('Food Design', '2025-01-15', 'images/Admin/affiches/food flyer design_.jpeg', NOW() - INTERVAL 26 DAY),
        ('Corporate Design', '2025-02-15', 'images/Admin/affiches/Minimal Corporate Flyer Design PSD Template - PSD Zone.jpeg', NOW() - INTERVAL 27 DAY),
        ('Coffee Ice', '2025-03-15', 'images/Admin/affiches/Ice Coffee.jpeg', NOW() - INTERVAL 28 DAY),
        ('Starbucks Design', '2025-04-15', 'images/Admin/sites/Design for Starbucks_.jpeg', NOW() - INTERVAL 29 DAY),
        ('BTP Construction', '2025-05-15', 'images/Admin/affiches/AficheBtp2.jpg', NOW() - INTERVAL 30 DAY),
        ('Affiche Premium', '2024-01-20', 'images/Admin/affiches/afiche1.jpg', NOW() - INTERVAL 31 DAY),
        ('Affiche Classic', '2024-02-20', 'images/Admin/affiches/Affiche5.jpg', NOW() - INTERVAL 32 DAY),
        ('Affiche Modern', '2024-03-20', 'images/Admin/affiches/Affiche7.jpg', NOW() - INTERVAL 33 DAY),
        ('Flyer Design', '2024-04-20', 'images/Admin/affiches/FLIER.jpg', NOW() - INTERVAL 34 DAY),
        ('Skincare Line', '2024-05-20', 'images/Admin/affiches/SkincareBrand.jpeg', NOW() - INTERVAL 35 DAY),
        ('Coffee Bar', '2024-06-20', 'images/Admin/affiches/Coffee shop drink.jpeg', NOW() - INTERVAL 36 DAY),
        ('Studio Creative', '2024-07-20', 'images/Admin/affiches/StudioFlyer.jpeg', NOW() - INTERVAL 37 DAY),
        ('Urban Food', '2024-08-20', 'images/Admin/affiches/urban grill.jpeg', NOW() - INTERVAL 38 DAY),
        ('Zest Store', '2024-09-20', 'images/Admin/affiches/zestmarket.jpeg', NOW() - INTERVAL 39 DAY),
        ('Iced Delight', '2024-10-20', 'images/Admin/affiches/Coffee Iced Coffee.jpeg', NOW() - INTERVAL 40 DAY),
        ('Grand Opening', '2024-11-20', 'images/Admin/affiches/GrandLaunch.jpeg', NOW() - INTERVAL 41 DAY),
        ('Food Menu', '2024-12-20', 'images/Admin/affiches/food flyer design_.jpeg', NOW() - INTERVAL 42 DAY),
        ('Minimal Design', '2025-01-20', 'images/Admin/affiches/Minimal Corporate Flyer Design PSD Template - PSD Zone.jpeg', NOW() - INTERVAL 43 DAY),
        ('Ice Delight', '2025-02-20', 'images/Admin/affiches/Ice Coffee.jpeg', NOW() - INTERVAL 44 DAY),
        ('Coffee Design', '2025-03-20', 'images/Admin/sites/Design for Starbucks_.jpeg', NOW() - INTERVAL 45 DAY),
        ('BTP Pro', '2025-04-20', 'images/Admin/affiches/AficheBtp2.jpg', NOW() - INTERVAL 46 DAY),
        ('Affiche Elite', '2025-05-20', 'images/Admin/affiches/afiche1.jpg', NOW() - INTERVAL 47 DAY),
        ('Affiche Style', '2025-06-20', 'images/Admin/affiches/Affiche5.jpg', NOW() - INTERVAL 48 DAY),
        ('Affiche Trend', '2024-01-25', 'images/Admin/affiches/Affiche7.jpg', NOW() - INTERVAL 49 DAY),
        ('Flyer Pro', '2024-02-25', 'images/Admin/affiches/FLIER.jpg', NOW() - INTERVAL 50 DAY);
    END IF;
    
    -- Insérer les identités seulement si la table est vide
    IF identites_count = 0 THEN
        INSERT INTO identites (client_name, date_realisation, image_path, created_at) VALUES
        ('Brand Identity 1', '2024-01-01', 'images/Admin/identités/logo1.png', NOW() - INTERVAL 1 DAY),
        ('Brand Identity 2', '2024-02-01', 'images/Admin/identités/logo2.png', NOW() - INTERVAL 2 DAY),
        ('Brand Identity 3', '2024-03-01', 'images/Admin/identités/logo3.png', NOW() - INTERVAL 3 DAY),
        ('Brand Identity 4', '2024-04-01', 'images/Admin/identités/logo4.png', NOW() - INTERVAL 4 DAY),
        ('Brand Identity 5', '2024-05-01', 'images/Admin/identités/logo5.png', NOW() - INTERVAL 5 DAY),
        ('Brand Identity 6', '2024-06-01', 'images/Admin/identités/logo6.png', NOW() - INTERVAL 6 DAY),
        ('Brand Identity 7', '2024-07-01', 'images/Admin/identités/logo7.png', NOW() - INTERVAL 7 DAY),
        ('Brand Identity 8', '2024-08-01', 'images/Admin/identités/logo8.png', NOW() - INTERVAL 8 DAY),
        ('Brand Identity 9', '2024-09-01', 'images/Admin/identités/logo9.png', NOW() - INTERVAL 9 DAY),
        ('Brand Identity 10', '2024-10-01', 'images/Admin/identités/logo10.png', NOW() - INTERVAL 10 DAY),
        ('Brand Identity 11', '2024-11-01', 'images/Admin/identités/logo11.png', NOW() - INTERVAL 11 DAY),
        ('Brand Identity 12', '2024-12-01', 'images/Admin/identités/logo12.png', NOW() - INTERVAL 12 DAY),
        ('Brand Identity 13', '2025-01-01', 'images/Admin/identités/logo13.png', NOW() - INTERVAL 13 DAY),
        ('Brand Identity 14', '2025-02-01', 'images/Admin/identités/logo14.png', NOW() - INTERVAL 14 DAY),
        ('Brand Identity 15', '2025-03-01', 'images/Admin/identités/logo15.png', NOW() - INTERVAL 15 DAY),
        ('Brand Identity 16', '2024-01-15', 'images/Admin/identités/logo16.png', NOW() - INTERVAL 16 DAY),
        ('Brand Identity 17', '2024-02-15', 'images/Admin/identités/logo17.png', NOW() - INTERVAL 17 DAY),
        ('Brand Identity 18', '2024-03-15', 'images/Admin/identités/logo18.png', NOW() - INTERVAL 18 DAY),
        ('Brand Identity 19', '2024-04-15', 'images/Admin/identités/logo19.png', NOW() - INTERVAL 19 DAY),
        ('Brand Identity 20', '2024-05-15', 'images/Admin/identités/logo20.png', NOW() - INTERVAL 20 DAY),
        ('Brand Identity 21', '2024-06-15', 'images/Admin/identités/logo21.png', NOW() - INTERVAL 21 DAY),
        ('Brand Identity 22', '2024-07-15', 'images/Admin/identités/logo22.png', NOW() - INTERVAL 22 DAY),
        ('Brand Identity 23', '2024-08-15', 'images/Admin/identités/logo23.png', NOW() - INTERVAL 23 DAY),
        ('Brand Identity 24', '2024-09-15', 'images/Admin/identités/logo24.png', NOW() - INTERVAL 24 DAY),
        ('Brand Identity 25', '2024-10-15', 'images/Admin/identités/logo25.png', NOW() - INTERVAL 25 DAY),
        ('Brand Identity 26', '2024-11-15', 'images/Admin/identités/logo26.png', NOW() - INTERVAL 26 DAY),
        ('Brand Identity 27', '2024-12-15', 'images/Admin/identités/logo27.png', NOW() - INTERVAL 27 DAY),
        ('Brand Identity 28', '2025-01-15', 'images/Admin/identités/logo28.png', NOW() - INTERVAL 28 DAY),
        ('Brand Identity 29', '2025-02-15', 'images/Admin/identités/logo29.png', NOW() - INTERVAL 29 DAY),
        ('Brand Identity 30', '2025-03-15', 'images/Admin/identités/logo30.png', NOW() - INTERVAL 30 DAY),
        ('Brand Identity 31', '2024-01-20', 'images/Admin/identités/logo31.png', NOW() - INTERVAL 31 DAY),
        ('Brand Identity 32', '2024-02-20', 'images/Admin/identités/logo32.png', NOW() - INTERVAL 32 DAY),
        ('Brand Identity 33', '2024-03-20', 'images/Admin/identités/logo33.png', NOW() - INTERVAL 33 DAY),
        ('Brand Identity 34', '2024-04-20', 'images/Admin/identités/logo34.png', NOW() - INTERVAL 34 DAY),
        ('Brand Identity 35', '2024-05-20', 'images/Admin/identités/logo35.png', NOW() - INTERVAL 35 DAY),
        ('Brand Identity 36', '2024-06-20', 'images/Admin/identités/logo36.png', NOW() - INTERVAL 36 DAY),
        ('Brand Identity 37', '2024-07-20', 'images/Admin/identités/logo37.png', NOW() - INTERVAL 37 DAY),
        ('Brand Identity 38', '2024-08-20', 'images/Admin/identités/logo38.png', NOW() - INTERVAL 38 DAY),
        ('Brand Identity 39', '2024-09-20', 'images/Admin/identités/logo39.png', NOW() - INTERVAL 39 DAY),
        ('Brand Identity 40', '2024-10-20', 'images/Admin/identités/logo40.png', NOW() - INTERVAL 40 DAY),
        ('Brand Identity 41', '2024-11-20', 'images/Admin/identités/logo41.png', NOW() - INTERVAL 41 DAY),
        ('Brand Identity 42', '2024-12-20', 'images/Admin/identités/logo42.png', NOW() - INTERVAL 42 DAY),
        ('Brand Identity 43', '2025-01-20', 'images/Admin/identités/logo43.png', NOW() - INTERVAL 43 DAY),
        ('Brand Identity 44', '2025-02-20', 'images/Admin/identités/logo44.png', NOW() - INTERVAL 44 DAY),
        ('Brand Identity 45', '2025-03-20', 'images/Admin/identités/logo45.png', NOW() - INTERVAL 45 DAY),
        ('Brand Identity 46', '2025-04-20', 'images/Admin/identités/logo46.png', NOW() - INTERVAL 46 DAY),
        ('Brand Identity 47', '2025-05-20', 'images/Admin/identités/logo47.png', NOW() - INTERVAL 47 DAY),
        ('Brand Identity 48', '2025-06-20', 'images/Admin/identités/logo48.png', NOW() - INTERVAL 48 DAY),
        ('Brand Identity 49', '2024-01-25', 'images/Admin/identités/logo49.png', NOW() - INTERVAL 49 DAY),
        ('Brand Identity 50', '2024-02-25', 'images/Admin/identités/logo50.png', NOW() - INTERVAL 50 DAY);
    END IF;
    
    -- Insérer les shootings seulement si la table est vide
    IF shootings_count = 0 THEN
        INSERT INTO shootings (client_name, date_realisation, image_path, created_at) VALUES
        ('Shooting 1', '2024-01-01', 'images/Admin/Shoot/shoot1.jpg', NOW() - INTERVAL 1 DAY),
        ('Shooting 2', '2024-02-01', 'images/Admin/Shoot/shoot2.jpg', NOW() - INTERVAL 2 DAY),
        ('Shooting 3', '2024-03-01', 'images/Admin/Shoot/shoot3.jpg', NOW() - INTERVAL 3 DAY),
        ('Shooting 4', '2024-04-01', 'images/Admin/Shoot/shoot4.jpg', NOW() - INTERVAL 4 DAY),
        ('Shooting 5', '2024-05-01', 'images/Admin/Shoot/shoot5.jpg', NOW() - INTERVAL 5 DAY),
        ('Shooting 6', '2024-06-01', 'images/Admin/Shoot/shoot6.jpg', NOW() - INTERVAL 6 DAY),
        ('Shooting 7', '2024-07-01', 'images/Admin/Shoot/shoot7.jpg', NOW() - INTERVAL 7 DAY),
        ('Shooting 8', '2024-08-01', 'images/Admin/Shoot/shoot8.jpg', NOW() - INTERVAL 8 DAY),
        ('Shooting 9', '2024-09-01', 'images/Admin/Shoot/shoot9.jpg', NOW() - INTERVAL 9 DAY),
        ('Shooting 10', '2024-10-01', 'images/Admin/Shoot/shoot10.jpg', NOW() - INTERVAL 10 DAY),
        ('Shooting 11', '2024-11-01', 'images/Admin/Shoot/shoot11.jpg', NOW() - INTERVAL 11 DAY),
        ('Shooting 12', '2024-12-01', 'images/Admin/Shoot/shoot12.jpg', NOW() - INTERVAL 12 DAY),
        ('Shooting 13', '2025-01-01', 'images/Admin/Shoot/shoot13.jpg', NOW() - INTERVAL 13 DAY),
        ('Shooting 14', '2025-02-01', 'images/Admin/Shoot/shoot14.jpg', NOW() - INTERVAL 14 DAY),
        ('Shooting 15', '2025-03-01', 'images/Admin/Shoot/shoot15.jpg', NOW() - INTERVAL 15 DAY),
        ('Shooting 16', '2024-01-15', 'images/Admin/Shoot/shoot16.jpg', NOW() - INTERVAL 16 DAY),
        ('Shooting 17', '2024-02-15', 'images/Admin/Shoot/shoot17.jpg', NOW() - INTERVAL 17 DAY),
        ('Shooting 18', '2024-03-15', 'images/Admin/Shoot/shoot18.jpg', NOW() - INTERVAL 18 DAY),
        ('Shooting 19', '2024-04-15', 'images/Admin/Shoot/shoot19.jpg', NOW() - INTERVAL 19 DAY),
        ('Shooting 20', '2024-05-15', 'images/Admin/Shoot/shoot20.jpg', NOW() - INTERVAL 20 DAY),
        ('Shooting 21', '2024-06-15', 'images/Admin/Shoot/shoot21.jpg', NOW() - INTERVAL 21 DAY),
        ('Shooting 22', '2024-07-15', 'images/Admin/Shoot/shoot22.jpg', NOW() - INTERVAL 22 DAY),
        ('Shooting 23', '2024-08-15', 'images/Admin/Shoot/shoot23.jpg', NOW() - INTERVAL 23 DAY),
        ('Shooting 24', '2024-09-15', 'images/Admin/Shoot/shoot24.jpg', NOW() - INTERVAL 24 DAY),
        ('Shooting 25', '2024-10-15', 'images/Admin/Shoot/shoot25.jpg', NOW() - INTERVAL 25 DAY),
        ('Shooting 26', '2024-11-15', 'images/Admin/Shoot/shoot26.jpg', NOW() - INTERVAL 26 DAY),
        ('Shooting 27', '2024-12-15', 'images/Admin/Shoot/shoot27.jpg', NOW() - INTERVAL 27 DAY),
        ('Shooting 28', '2025-01-15', 'images/Admin/Shoot/shoot28.jpg', NOW() - INTERVAL 28 DAY),
        ('Shooting 29', '2025-02-15', 'images/Admin/Shoot/shoot29.jpg', NOW() - INTERVAL 29 DAY),
        ('Shooting 30', '2025-03-15', 'images/Admin/Shoot/shoot30.jpg', NOW() - INTERVAL 30 DAY),
        ('Shooting 31', '2024-01-20', 'images/Admin/Shoot/shoot31.jpg', NOW() - INTERVAL 31 DAY),
        ('Shooting 32', '2024-02-20', 'images/Admin/Shoot/shoot32.jpg', NOW() - INTERVAL 32 DAY),
        ('Shooting 33', '2024-03-20', 'images/Admin/Shoot/shoot33.jpg', NOW() - INTERVAL 33 DAY),
        ('Shooting 34', '2024-04-20', 'images/Admin/Shoot/shoot34.jpg', NOW() - INTERVAL 34 DAY),
        ('Shooting 35', '2024-05-20', 'images/Admin/Shoot/shoot35.jpg', NOW() - INTERVAL 35 DAY),
        ('Shooting 36', '2024-06-20', 'images/Admin/Shoot/shoot36.jpg', NOW() - INTERVAL 36 DAY),
        ('Shooting 37', '2024-07-20', 'images/Admin/Shoot/shoot37.jpg', NOW() - INTERVAL 37 DAY),
        ('Shooting 38', '2024-08-20', 'images/Admin/Shoot/shoot38.jpg', NOW() - INTERVAL 38 DAY),
        ('Shooting 39', '2024-09-20', 'images/Admin/Shoot/shoot39.jpg', NOW() - INTERVAL 39 DAY),
        ('Shooting 40', '2024-10-20', 'images/Admin/Shoot/shoot40.jpg', NOW() - INTERVAL 40 DAY),
        ('Shooting 41', '2024-11-20', 'images/Admin/Shoot/shoot41.jpg', NOW() - INTERVAL 41 DAY),
        ('Shooting 42', '2024-12-20', 'images/Admin/Shoot/shoot42.jpg', NOW() - INTERVAL 42 DAY),
        ('Shooting 43', '2025-01-20', 'images/Admin/Shoot/shoot43.jpg', NOW() - INTERVAL 43 DAY),
        ('Shooting 44', '2025-02-20', 'images/Admin/Shoot/shoot44.jpg', NOW() - INTERVAL 44 DAY),
        ('Shooting 45', '2025-03-20', 'images/Admin/Shoot/shoot45.jpg', NOW() - INTERVAL 45 DAY),
        ('Shooting 46', '2025-04-20', 'images/Admin/Shoot/shoot46.jpg', NOW() - INTERVAL 46 DAY),
        ('Shooting 47', '2025-05-20', 'images/Admin/Shoot/shoot47.jpg', NOW() - INTERVAL 47 DAY),
        ('Shooting 48', '2025-06-20', 'images/Admin/Shoot/shoot48.jpg', NOW() - INTERVAL 48 DAY),
        ('Shooting 49', '2024-01-25', 'images/Admin/Shoot/shoot49.jpg', NOW() - INTERVAL 49 DAY),
        ('Shooting 50', '2024-02-25', 'images/Admin/Shoot/shoot50.jpg', NOW() - INTERVAL 50 DAY);
    END IF;
END //

DELIMITER ;

-- Exécuter la procédure
CALL seed_if_empty();

-- Supprimer la procédure après utilisation
DROP PROCEDURE IF EXISTS seed_if_empty;

-- Réactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- Afficher le nombre d'éléments insérés
SELECT 
    (SELECT COUNT(*) FROM messages) as total_messages,
    (SELECT COUNT(*) FROM sites) as total_sites,
    (SELECT COUNT(*) FROM affiches) as total_affiches,
    (SELECT COUNT(*) FROM identites) as total_identites,
    (SELECT COUNT(*) FROM shootings) as total_shootings,
    (SELECT COUNT(*) FROM messages) + 
    (SELECT COUNT(*) FROM sites) + 
    (SELECT COUNT(*) FROM affiches) + 
    (SELECT COUNT(*) FROM identites) + 
    (SELECT COUNT(*) FROM shootings) as total_elements;
