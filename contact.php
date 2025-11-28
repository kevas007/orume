<?php
/**
 * ============================================
 * PAGE DE CONTACT - FRONTEND PUBLIC
 * ============================================
 * 
 * Cette page affiche le formulaire de contact et traite
 * les soumissions pour enregistrer les messages dans la base de données.
 * 
 * @package Orüme
 * @version 1.0.0
 */

// Traiter la soumission du formulaire si méthode POST
$messageFlash = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/partials/connect.php';
    
    // Récupérer et valider les données
    $nom = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $sujet = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validation
    $errors = [];
    if (empty($nom)) $errors[] = 'Le nom est requis';
    if (empty($email)) $errors[] = 'L\'email est requis';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'L\'email n\'est pas valide';
    if (empty($sujet)) $errors[] = 'Le sujet est requis';
    if (empty($message)) $errors[] = 'Le message est requis';
    elseif (strlen($message) < 10) $errors[] = 'Le message doit contenir au moins 10 caractères';
    
    // Si pas d'erreurs, sauvegarder
    if (empty($errors) && isset($connect) && $connect) {
        $nom = mysqli_real_escape_string($connect, $nom);
        $email = mysqli_real_escape_string($connect, $email);
        $sujet = mysqli_real_escape_string($connect, $sujet);
        $message = mysqli_real_escape_string($connect, $message);
        
        $query = "INSERT INTO messages (nom, email, sujet, message, statut) VALUES ('$nom', '$email', '$sujet', '$message', 'non_lu')";
        
        if (mysqli_query($connect, $query)) {
            $messageFlash = 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.';
            $messageType = 'success';
            // Réinitialiser les champs
            $nom = $email = $sujet = $message = '';
        } else {
            $messageFlash = 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.';
            $messageType = 'error';
        }
    } else {
        $messageFlash = implode('<br>', $errors);
        $messageType = 'error';
    }
}

// Code HTML (sera utilisé si le contrôleur ne gère pas l'affichage)
?>
<!DOCTYPE html>
<html lang="fr">
<?php include 'partials/head.php'; ?>

<!-- HERO SECTION CONTACT -->
<section class="contact-hero">
    <div class="contact-hero-content">
        <h1>Contactez-Nous</h1>
        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut</p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <!-- Formulaire -->
            <div class="contact-form">
                <span class="contact-subtitle">-Contactez-nous</span>
                <h3>Obtenez votre <span>devis aujourd'hui</span></h3>
                
                <!-- Afficher les messages flash -->
                <?php if (!empty($messageFlash)): ?>
                    <div class="alert <?php echo $messageType === 'success' ? 'alert-success' : 'alert-error'; ?>" style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background: <?php echo $messageType === 'success' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $messageType === 'success' ? '#155724' : '#721c24'; ?>;">
                        <?php echo htmlspecialchars($messageFlash, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="contact.php">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Votre Nom</label>
                            <input type="text" id="name" name="name" placeholder="example@gmail.com" value="<?php echo isset($nom) ? htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" placeholder="example@gmail.com" value="<?php echo isset($email) ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">Sujet</label>
                        <input type="text" id="subject" name="subject" placeholder="Mail" value="<?php echo isset($sujet) ? htmlspecialchars($sujet, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Votre Message</label>
                        <textarea id="message" name="message" rows="4" placeholder="Message" required minlength="10"><?php echo isset($message) ? htmlspecialchars($message, ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    </div>

                    <button type="submit" class="btn-send">Envoyez</button>
                </form>
            </div>

            <!-- Bloc coordonnées -->
            <div class="contact-info">
                <h4>Adress</h4>
                <p>253 rue HDN, Hedzranawoe,<br>Piscine Atlantide.</p>

                <h4>Contact</h4>
                <p>Phone: +228 99 21 50 63<br>Email: orumetg228@gmail.com</p>

                <h4>Horaires</h4>
                <p>Lundi-Vendredi: 08:00-17:00<br>Samedi: 09:00-12:00</p>

                <h4>Suivez-nous</h4>
                <div class="socials">
                    <a href="#" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon tiktok"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Map -->
<section class="map-section">
    <div class="map-container">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.5!2d1.2167!3d6.1375!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1023e34b8b3b3b3b%3A0x8b3b3b3b3b3b3b3b!2sLom%C3%A9%2C%20Togo!5e0!3m2!1sfr!2stg!4v1700000000000!5m2!1sfr!2stg"
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade"
            class="map-iframe"
            title="Carte de localisation - Orüme, Lomé, Togo">
        </iframe>
    </div>
</section>

<!-- Section qualités -->
<section class="qualities-section">
    <div class="container">
        <div class="qualities-container">
            <div class="quality">
                <div class="quality-icon">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="quality-number">15</span>
                </div>
                <p>Nous délivrons dans de bref délai</p>
            </div>
            <div class="quality">
                <div class="quality-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <p>Equipe d'expert et professionnel</p>
            </div>
            <div class="quality">
                <div class="quality-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <p>Nous répondons a vos besoins</p>
            </div>
        </div>
    </div>
</section>

<!-- Section qualités bandeau orange -->
<section class="qualities-banner">
    <div class="qualities-banner-content">
        <span>Flexibilité</span>
        <span class="star">*</span>
        <span>Créativité</span>
        <span class="star">*</span>
        <span>Professionalisme</span>
        <span class="star">*</span>
        <span>Rigueur</span>
    </div>
</section>

<?php
      include'partials/footer.php'
  ?>


</body>
</html>
