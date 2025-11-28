<?php
/**
 * ============================================
 * API - METTRE À JOUR LE STATUT D'UN MESSAGE
 * ============================================
 * 
 * Endpoint pour mettre à jour le statut d'un message (lu/non_lu/repondu)
 * 
 * @package Orüme\Admin\API
 * @version 1.0.0
 */

header('Content-Type: application/json');

// Vérifier que la requête est en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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

// Récupérer les données
$id = intval($_POST['id'] ?? 0);
$statut = $_POST['statut'] ?? '';

// Valider les données
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID invalide']);
    exit;
}

// Valider le statut
$statutsValides = ['non_lu', 'lu', 'repondu'];
if (!in_array($statut, $statutsValides)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Statut invalide']);
    exit;
}

// Échapper le statut pour la requête SQL
$statutEscaped = mysqli_real_escape_string($connect, $statut);

// Mettre à jour le statut
$query = "UPDATE messages SET statut = '$statutEscaped' WHERE id = $id";

if (mysqli_query($connect, $query)) {
    echo json_encode([
        'success' => true, 
        'message' => 'Statut mis à jour avec succès',
        'statut' => $statut
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour : ' . mysqli_error($connect)]);
}

