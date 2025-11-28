<?php
/**
 * ============================================
 * API - AJOUTER UN SHOOTING
 * ============================================
 * 
 * Endpoint pour ajouter un nouveau shooting dans le portfolio
 * 
 * @package Orüme\Admin\API
 * @version 1.0.0
 */

header('Content-Type: application/json');

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

$clientName = trim($_POST['clientName'] ?? '');
$dateRealisation = $_POST['dateRealisation'] ?? '';
$imageFile = $_FILES['image'] ?? null;

$errors = [];
if (empty($clientName)) $errors[] = 'Le nom du client est requis';
if (empty($dateRealisation)) $errors[] = 'La date de réalisation est requise';
if (!$imageFile || $imageFile['error'] !== UPLOAD_ERR_OK) $errors[] = 'L\'image est requise';

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Upload de l'image
$uploadDir = __DIR__ . '/../images/Admin/Shoot/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '_' . time() . '.' . $extension;
$uploadPath = $uploadDir . $filename;
$imagePath = 'images/Admin/Shoot/' . $filename;

if (!move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'upload de l\'image']);
    exit;
}

// Insérer dans la base de données
$clientName = mysqli_real_escape_string($connect, $clientName);
$imagePath = mysqli_real_escape_string($connect, $imagePath);
$dateRealisation = mysqli_real_escape_string($connect, $dateRealisation . '-01');

$query = "INSERT INTO shootings (client_name, date_realisation, image_path) 
          VALUES ('$clientName', '$dateRealisation', '$imagePath')";

if (mysqli_query($connect, $query)) {
    $id = mysqli_insert_id($connect);
    echo json_encode([
        'success' => true, 
        'message' => 'Shooting ajouté avec succès',
        'id' => $id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout : ' . mysqli_error($connect)]);
}

