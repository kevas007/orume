<?php
/**
 * ============================================
 * API - AJOUTER UN UTILISATEUR
 * ============================================
 * 
 * Endpoint pour ajouter un nouvel utilisateur (admin uniquement)
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

// Récupérer les données JSON
$data = json_decode(file_get_contents('php://input'), true);

$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$role = trim($data['role'] ?? 'user');

// Validation
$errors = [];
if (empty($username)) $errors[] = 'Le nom d\'utilisateur est requis';
if (empty($email)) $errors[] = 'L\'email est requis';
if (empty($password)) $errors[] = 'Le mot de passe est requis';
if (!in_array($role, ['admin', 'user'])) $errors[] = 'Rôle invalide';

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Vérifier si l'username existe déjà
$stmt = mysqli_prepare($connect, "SELECT id FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    mysqli_stmt_close($stmt);
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ce nom d\'utilisateur existe déjà']);
    exit;
}
mysqli_stmt_close($stmt);

// Vérifier si l'email existe déjà
$stmt = mysqli_prepare($connect, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    mysqli_stmt_close($stmt);
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Cet email existe déjà']);
    exit;
}
mysqli_stmt_close($stmt);

// Hasher le mot de passe
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insérer l'utilisateur
$stmt = mysqli_prepare($connect, "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashedPassword, $role);

if (mysqli_stmt_execute($stmt)) {
    $userId = mysqli_insert_id($connect);
    mysqli_stmt_close($stmt);
    echo json_encode([
        'success' => true,
        'message' => 'Utilisateur créé avec succès',
        'user_id' => $userId
    ]);
} else {
    mysqli_stmt_close($stmt);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de l\'utilisateur']);
}

