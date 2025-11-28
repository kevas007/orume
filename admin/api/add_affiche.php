<?php
/**
 * ============================================
 * API - AJOUTER UNE AFFICHE
 * ============================================
 * 
 * Endpoint pour ajouter une nouvelle affiche dans le portfolio
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

$clientName = trim($_POST['clientAffiche'] ?? '');
$dateRealisation = $_POST['dateAffiche'] ?? '';
$imageFile = $_FILES['imageAffiche'] ?? null;

// Debug: logger les données reçues
error_log("API add_affiche - clientAffiche: " . $clientName);
error_log("API add_affiche - dateAffiche: " . $dateRealisation);
error_log("API add_affiche - imageAffiche: " . ($imageFile ? 'présent' : 'absent'));
if ($imageFile) {
    error_log("API add_affiche - image error: " . $imageFile['error']);
}

$errors = [];
if (empty($clientName)) $errors[] = 'Le nom du client est requis';
if (empty($dateRealisation)) $errors[] = 'La date de réalisation est requise';
if (!$imageFile || $imageFile['error'] !== UPLOAD_ERR_OK) {
    $errorMsg = 'L\'image est requise';
    if ($imageFile && $imageFile['error'] !== UPLOAD_ERR_OK) {
        $errorMsg .= ' (erreur upload: ' . $imageFile['error'] . ')';
    }
    $errors[] = $errorMsg;
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Upload de l'image
$uploadDir = __DIR__ . '/../images/Admin/affiches/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '_' . time() . '.' . $extension;
$uploadPath = $uploadDir . $filename;
$imagePath = 'images/Admin/affiches/' . $filename;

if (!move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'upload de l\'image']);
    exit;
}

// Insérer dans la base de données
$clientName = mysqli_real_escape_string($connect, $clientName);
$imagePath = mysqli_real_escape_string($connect, $imagePath);
$dateRealisation = mysqli_real_escape_string($connect, $dateRealisation . '-01');

$query = "INSERT INTO affiches (client_name, date_realisation, image_path) 
          VALUES ('$clientName', '$dateRealisation', '$imagePath')";

if (mysqli_query($connect, $query)) {
    $id = mysqli_insert_id($connect);
    echo json_encode([
        'success' => true, 
        'message' => 'Affiche ajoutée avec succès',
        'id' => $id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout : ' . mysqli_error($connect)]);
}

