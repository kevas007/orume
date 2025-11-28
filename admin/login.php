<?php
/**
 * ============================================
 * PAGE DE CONNEXION ADMIN
 * ============================================
 * 
 * Page de connexion pour accéder à l'interface d'administration.
 * 
 * @package Orüme\Admin
 * @version 1.0.0
 */

// Démarrer la session
session_start();

// Si l'utilisateur est déjà connecté, rediriger vers le dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    require_once __DIR__ . '/../partials/connect.php';
    
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        if (isset($connect) && $connect) {
            // Préparer la requête pour éviter les injections SQL
            $stmt = mysqli_prepare($connect, "SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ?");
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $username, $username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if ($user = mysqli_fetch_assoc($result)) {
                    // Vérifier le mot de passe
                    if (password_verify($password, $user['password'])) {
                        // Connexion réussie
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['role'] = $user['role'];
                        
                        // Rediriger vers le dashboard
                        header('Location: index.php');
                        exit;
                    } else {
                        $error = 'Nom d\'utilisateur ou mot de passe incorrect';
                    }
                } else {
                    $error = 'Nom d\'utilisateur ou mot de passe incorrect';
                }
                
                mysqli_stmt_close($stmt);
            } else {
                $error = 'Erreur de connexion à la base de données';
            }
        } else {
            $error = 'Erreur de connexion à la base de données';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orüme | Connexion Admin</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  <!-- CSS externe -->
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <div class="container" id="container">
    
    <!-- -------- PANEL GAUCHE -------- -->
    <div class="panel left">
      <h2>Bienvenue sur Orüme !</h2>
      <p>Connectez-vous pour gérer vos créations et suivre vos projets.</p>
      <button class="btn" id="signIn">Se connecter</button>
    </div>

    <!-- -------- PANEL DROIT -------- -->
    <div class="panel right">
      <!-- Formulaire de connexion -->
      <form class="sign-in-form" method="POST" action="">
        <h2>Connexion</h2>
        
        <?php if ($error): ?>
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
          </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
          </div>
        <?php endif; ?>
        
        <div class="input-field">
          <i class="fa-solid fa-user"></i>
          <input type="text" name="username" placeholder="Email ou nom d'utilisateur" required autofocus>
        </div>
        <div class="input-field">
          <i class="fa-solid fa-lock"></i>
          <input type="password" name="password" placeholder="Mot de passe" required>
        </div>
        <button type="submit" name="login" class="btn">Se connecter</button>
      </form>
    </div>
  </div>

  <!-- Script -->
  <script>
    // Garder uniquement le mode connexion
    const container = document.getElementById('container');
    const signIn = document.getElementById('signIn');

    signIn.addEventListener('click', () => {
      container.classList.remove('sign-up-mode');
    });
  </script>
</body>
</html>

