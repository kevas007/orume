<?php
/**
 * ============================================
 * API - SUPPRIMER UN UTILISATEUR
 * ============================================
 * 
 * Endpoint pour supprimer un utilisateur (admin uniquement)
 * 
 * @package Orüme\Admin\API
 * @version 1.0.0
 */

header('Content-Type: application/json');

// Démarrer la session
session_start();

// Vérifier l'authentification et les droits admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Accès refusé. Administrateur requis.']);
    exit;
}

// Vérifier que la requête est en DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
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

// Récupérer l'ID depuis l'URL
$userId = $_GET['id'] ?? null;
if (empty($userId)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID utilisateur manquant']);
    exit;
}

// Empêcher la suppression de son propre compte
if ($userId == $_SESSION['user_id']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas supprimer votre propre compte']);
    exit;
}

// Vérifier si l'utilisateur existe
$stmt = mysqli_prepare($connect, "SELECT id FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
    exit;
}
mysqli_stmt_close($stmt);

// Supprimer l'utilisateur
$stmt = mysqli_prepare($connect, "DELETE FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $userId);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    echo json_encode([
        'success' => true,
        'message' => 'Utilisateur supprimé avec succès'
    ]);
} else {
    mysqli_stmt_close($stmt);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression de l\'utilisateur']);
}

