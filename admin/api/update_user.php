<?php
/**
 * ============================================
 * API - MODIFIER UN UTILISATEUR
 * ============================================
 * 
 * Endpoint pour modifier un utilisateur existant (admin uniquement)
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

// Vérifier que la requête est en PUT
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

// Récupérer les données JSON
$data = json_decode(file_get_contents('php://input'), true);

$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? null;
$role = trim($data['role'] ?? 'user');

// Validation
$errors = [];
if (empty($username)) $errors[] = 'Le nom d\'utilisateur est requis';
if (empty($email)) $errors[] = 'L\'email est requis';
if (!in_array($role, ['admin', 'user'])) $errors[] = 'Rôle invalide';

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
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

// Vérifier si l'username existe déjà (pour un autre utilisateur)
$stmt = mysqli_prepare($connect, "SELECT id FROM users WHERE username = ? AND id != ?");
mysqli_stmt_bind_param($stmt, "si", $username, $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    mysqli_stmt_close($stmt);
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ce nom d\'utilisateur existe déjà']);
    exit;
}
mysqli_stmt_close($stmt);

// Vérifier si l'email existe déjà (pour un autre utilisateur)
$stmt = mysqli_prepare($connect, "SELECT id FROM users WHERE email = ? AND id != ?");
mysqli_stmt_bind_param($stmt, "si", $email, $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    mysqli_stmt_close($stmt);
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Cet email existe déjà']);
    exit;
}
mysqli_stmt_close($stmt);

// Construire la requête de mise à jour
if ($password) {
    // Mettre à jour avec le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($connect, "UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $hashedPassword, $role, $userId);
} else {
    // Mettre à jour sans le mot de passe
    $stmt = mysqli_prepare($connect, "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $role, $userId);
}

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    echo json_encode([
        'success' => true,
        'message' => 'Utilisateur modifié avec succès'
    ]);
} else {
    mysqli_stmt_close($stmt);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification de l\'utilisateur']);
}

