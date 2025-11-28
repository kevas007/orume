<?php
/**
 * ============================================
 * API - MODIFIER UNE IDENTITÉ VISUELLE
 * ============================================
 * 
 * Endpoint pour modifier une identité visuelle existante
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

$id = intval($_POST['id'] ?? 0);
$clientName = trim($_POST['clientName'] ?? '');
$dateRealisation = $_POST['dateRealisation'] ?? '';
$imageFile = $_FILES['image'] ?? null;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID invalide']);
    exit;
}

$errors = [];
if (empty($clientName)) $errors[] = 'Le nom du client est requis';
if (empty($dateRealisation)) $errors[] = 'La date de réalisation est requise';

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Récupérer l'ancien chemin de l'image
$querySelect = "SELECT image_path FROM identites WHERE id = $id";
$result = mysqli_query($connect, $querySelect);
$oldRow = mysqli_fetch_assoc($result);
$oldImagePath = $oldRow['image_path'] ?? '';

$imagePath = $oldImagePath; // Garder l'ancien chemin par défaut

// Si une nouvelle image est fournie
if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../images/Admin/identités/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $uploadPath = $uploadDir . $filename;
    $imagePath = 'images/Admin/identités/' . $filename;

    if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
        // Supprimer l'ancienne image si elle existe
        if ($oldImagePath && file_exists(__DIR__ . '/../' . $oldImagePath)) {
            @unlink(__DIR__ . '/../' . $oldImagePath);
        }
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'upload de l\'image']);
        exit;
    }
}

// Mettre à jour dans la base de données
$clientName = mysqli_real_escape_string($connect, $clientName);
$imagePath = mysqli_real_escape_string($connect, $imagePath);
$dateRealisation = mysqli_real_escape_string($connect, $dateRealisation . '-01');

$query = "UPDATE identites 
          SET client_name = '$clientName', 
              date_realisation = '$dateRealisation', 
              image_path = '$imagePath'
          WHERE id = $id";

if (mysqli_query($connect, $query)) {
    echo json_encode([
        'success' => true, 
        'message' => 'Identité visuelle modifiée avec succès',
        'image_path' => $imagePath
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification : ' . mysqli_error($connect)]);
}

