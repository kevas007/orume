<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orüme | Connexion</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  <!-- CSS externe -->
  <link rel="stylesheet" href="../css/login.css">
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
      <form class="sign-in-form">
        <h2>Connexion</h2>
        <div class="input-field">
          <i class="fa-solid fa-user"></i>
          <input type="text" placeholder="Email ou nom d'utilisateur" required>
        </div>
        <div class="input-field">
          <i class="fa-solid fa-lock"></i>
          <input type="password" placeholder="Mot de passe" required>
        </div>
        <button type="submit" class="btn">Se connecter</button>
        <p>Vous n'avez pas encore de compte ? 
          <a href="#" id="switchToSignUp">Créer un compte</a>
        </p>
      </form>

      <!-- Formulaire d’inscription -->
      <form class="sign-up-form">
        <h2>Créer un compte</h2>
        <div class="social-icons">
          <i class="fab fa-facebook-f"></i>
          <i class="fab fa-google"></i>
          <i class="fab fa-linkedin-in"></i>
        </div>
        <p>ou utilisez votre adresse e-mail :</p>
        <div class="input-field">
          <i class="fa-solid fa-user"></i>
          <input type="text" placeholder="Nom complet" required>
        </div>
        <div class="input-field">
          <i class="fa-solid fa-envelope"></i>
          <input type="email" placeholder="Adresse e-mail" required>
        </div>
        <div class="input-field">
          <i class="fa-solid fa-lock"></i>
          <input type="password" placeholder="Mot de passe" required>
        </div>
        <button type="submit" class="btn">S'inscrire</button>
        <p>Déjà membre ? 
          <a href="#" id="switchToSignIn">Se connecter</a>
        </p>
      </form>
    </div>
  </div>

  <!-- Script -->
  <script>
    // ----- CHANGEMENT DE MODE -----
    const container = document.getElementById('container');
    const switchToSignUp = document.getElementById('switchToSignUp');
    const switchToSignIn = document.getElementById('switchToSignIn');
    const signIn = document.getElementById('signIn');

    switchToSignUp.addEventListener('click', (e) => {
      e.preventDefault();
      container.classList.add('sign-up-mode');
    });

    switchToSignIn.addEventListener('click', (e) => {
      e.preventDefault();
      container.classList.remove('sign-up-mode');
    });

    signIn.addEventListener('click', () => {
      container.classList.remove('sign-up-mode');
    });
  </script>
</body>
</html>
