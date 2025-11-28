<?php
/**
 * Script PHP pour vérifier si la base de données est vide
 * et exécuter automatiquement le seeder
 */

// Configuration de la base de données
$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'orume_user';
$password = getenv('DB_PASS') ?: 'orume_password';
$database = getenv('DB_NAME') ?: 'orume';

// Attendre que MySQL soit prêt (max 60 tentatives = 120 secondes)
$maxAttempts = 60;
$attempt = 0;
$connected = false;

echo "⏳ Attente de la disponibilité de MySQL...\n";

while ($attempt < $maxAttempts && !$connected) {
    try {
        $conn = new mysqli($host, $user, $password, $database);
        if ($conn->connect_error) {
            $attempt++;
            if ($attempt % 5 == 0) {
                echo "   Tentative $attempt/$maxAttempts...\n";
            }
            sleep(2);
        } else {
            $connected = true;
        }
    } catch (Exception $e) {
        $attempt++;
        sleep(2);
    }
}

if (!$connected) {
    echo "❌ Impossible de se connecter à MySQL après $maxAttempts tentatives\n";
    exit(1);
}

echo "✅ Connexion à MySQL établie\n";

// Fonction pour vérifier si une table existe
function tableExists($conn, $tableName) {
    $result = $conn->query("SHOW TABLES LIKE '$tableName'");
    return $result && $result->num_rows > 0;
}

// Fonction pour compter les enregistrements dans une table (retourne 0 si la table n'existe pas)
function countRecords($conn, $tableName) {
    if (!tableExists($conn, $tableName)) {
        return 0;
    }
    $result = $conn->query("SELECT COUNT(*) as count FROM `$tableName`");
    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['count'];
    }
    return 0;
}

// Fonction pour exécuter un fichier SQL
function executeSQLFile($conn, $filePath) {
    if (!file_exists($filePath)) {
        echo "⚠️  Fichier introuvable: $filePath\n";
        return false;
    }
    
    $sql = file_get_contents($filePath);
    if (empty(trim($sql))) {
        echo "⚠️  Le fichier est vide: $filePath\n";
        return false;
    }
    
    if ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->next_result());
        
        if ($conn->errno) {
            echo "⚠️  Erreur SQL: " . $conn->error . " (Code: " . $conn->errno . ")\n";
            return false;
        }
        return true;
    } else {
        echo "⚠️  Erreur lors de l'exécution: " . $conn->error . " (Code: " . $conn->errno . ")\n";
        return false;
    }
}

// Liste des tables principales à vérifier
$mainTables = ['users', 'messages', 'sites', 'affiches', 'identites', 'shootings'];

// Vérifier si les tables principales existent
$tablesExist = true;
foreach ($mainTables as $table) {
    if (!tableExists($conn, $table)) {
        $tablesExist = false;
        echo "⚠️  Table '$table' n'existe pas\n";
        break;
    }
}

// Si les tables n'existent pas, exécuter init.sql d'abord
if (!$tablesExist) {
    echo "📋 Les tables n'existent pas, exécution de init.sql...\n";
    $initFile = __DIR__ . '/init.sql';
    
    if (executeSQLFile($conn, $initFile)) {
        echo "✅ Tables créées avec succès\n";
    } else {
        echo "❌ Erreur lors de la création des tables\n";
        $conn->close();
        exit(1);
    }
}

// Liste des tables à vérifier pour les données
$tables = ['users', 'messages', 'sites', 'affiches', 'identites', 'shootings', 'clients'];

// Vérifier si la base de données est vide
$total = 0;
foreach ($tables as $table) {
    $count = countRecords($conn, $table);
    $total += $count;
    if ($count > 0) {
        echo "   Table '$table': $count enregistrement(s)\n";
    }
}

if ($total === 0) {
    echo "📦 La base de données est vide, exécution du seeder...\n";
    
    // Exécuter le fichier seed.sql
    $seedFile = __DIR__ . '/seed.sql';
    
    if (executeSQLFile($conn, $seedFile)) {
        echo "✅ Seeder exécuté avec succès\n";
        
        // Afficher le nombre d'enregistrements après le seed
        $newTotal = 0;
        foreach ($tables as $table) {
            $count = countRecords($conn, $table);
            $newTotal += $count;
            if ($count > 0) {
                echo "   ✓ Table '$table': $count enregistrement(s)\n";
            }
        }
        echo "📊 Total d'enregistrements après seed: $newTotal\n";
    } else {
        echo "❌ Erreur lors de l'exécution du seeder\n";
        $conn->close();
        exit(1);
    }
} else {
    echo "ℹ️  La base de données contient déjà des données ($total enregistrements), le seeder ne sera pas exécuté\n";
}

$conn->close();

