<?php
/**
 * ============================================
 * API - SUPPRIMER UNE AFFICHE
 * ============================================
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

require_once __DIR__ . '/../../partials/connect.php';

if (!isset($connect) || !$connect) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion']);
    exit;
}

$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID invalide']);
    exit;
}

// Récupérer le chemin de l'image
$querySelect = "SELECT image_path FROM affiches WHERE id = $id";
$result = mysqli_query($connect, $querySelect);
$row = mysqli_fetch_assoc($result);
$imagePath = $row['image_path'] ?? '';

// Supprimer de la base de données
$query = "DELETE FROM affiches WHERE id = $id";

if (mysqli_query($connect, $query)) {
    // Supprimer l'image
    if ($imagePath && file_exists(__DIR__ . '/../' . $imagePath)) {
        @unlink(__DIR__ . '/../' . $imagePath);
    }
    echo json_encode(['success' => true, 'message' => 'Affiche supprimée avec succès']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur : ' . mysqli_error($connect)]);
}

