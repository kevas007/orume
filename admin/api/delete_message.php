<?php
/**
 * ============================================
 * API - SUPPRIMER UN MESSAGE
 * ============================================
 * 
 * Endpoint pour supprimer un message de contact
 * 
 * @package Orüme\Admin\API
 * @version 1.0.0
 */

header('Content-Type: application/json');

// Vérifier que la requête est en POST ou DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

require_once __DIR__ . '/../../partials/connect.php';

if (!isset($connect) || !$connect) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données']);
    exit;
}

// Récupérer l'ID
$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID invalide']);
    exit;
}

// Supprimer de la base de données
$query = "DELETE FROM messages WHERE id = $id";

if (mysqli_query($connect, $query)) {
    echo json_encode(['success' => true, 'message' => 'Message supprimé avec succès']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression : ' . mysqli_error($connect)]);
}

