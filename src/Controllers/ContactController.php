<?php
/**
 * ============================================
 * CONTRÔLEUR POUR LE FORMULAIRE DE CONTACT
 * ============================================
 * 
 * Ce contrôleur gère les requêtes liées au formulaire de contact
 * public (affichage et traitement du formulaire).
 * 
 * @package Orüme\Controllers
 * @version 1.0.0
 */

namespace Orüme\Controllers;

use Orüme\Models\MessageModel;

class ContactController
{
    /**
     * Instance du modèle Message
     * @var MessageModel
     */
    private MessageModel $messageModel;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->messageModel = new MessageModel();
    }

    /**
     * Afficher la page de contact
     * 
     * @return void
     */
    public function index(): void
    {
        // Inclure la vue de contact
        require_once dirname(__DIR__, 2) . '/contact.php';
    }

    /**
     * Traiter la soumission du formulaire de contact
     * 
     * @return void
     */
    public function submit(): void
    {
        // Vérifier que la requête est en POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Méthode non autorisée');
        }

        // Récupérer et valider les données
        $nom = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $sujet = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Validation
        $errors = [];

        if (empty($nom)) {
            $errors[] = 'Le nom est requis';
        }

        if (empty($email)) {
            $errors[] = 'L\'email est requis';
        } elseif (!isValidEmail($email)) {
            $errors[] = 'L\'email n\'est pas valide';
        }

        if (empty($sujet)) {
            $errors[] = 'Le sujet est requis';
        }

        if (empty($message)) {
            $errors[] = 'Le message est requis';
        } elseif (strlen($message) < 10) {
            $errors[] = 'Le message doit contenir au moins 10 caractères';
        }

        // Si des erreurs, rediriger avec les erreurs
        if (!empty($errors)) {
            setFlashMessage('error', implode('<br>', $errors));
            redirect('/contact.php');
        }

        // Sauvegarder le message dans la base de données
        $data = [
            'nom' => $nom,
            'email' => $email,
            'sujet' => $sujet,
            'message' => $message,
            'statut' => 'non_lu'
        ];

        $result = $this->messageModel->create($data);

        if ($result) {
            setFlashMessage('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
        } else {
            setFlashMessage('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.');
        }

        redirect('/contact.php');
    }
}

