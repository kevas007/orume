<?php
// La session est déjà démarrée par auth.php
?>
 <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <h2 class="textorume">Orüme</h2>
      <button class="hamburger" id="hamburger">
        <i class="fas fa-bars"></i>
      </button>
    </div>
    
    <nav class="menu">
      <a href="index.php" class="activeAcceuil">
        <span class="icon"><i class="fas fa-home"></i></span>
        <span class="text">Acceuil</span>
      </a>
      <a href="Messages.php" class="activeMesssage">
        <span class="icon"><i class="fas fa-envelope"></i></span>
        <span class="text">Messages</span>
      </a>
      <a href="portfolio.php" class="active">
        <span class="icon"><i class="fa-solid fa-display"></i></span>
        <span class="text">Sites</span>
      </a>
      <a href="affiche.php" class="activeAffiche">
        <span class="icon"><i class="fas fa-image"></i></span>
        <span class="text">Affiches</span>
      </a>
      <a href="identites.php" class="activeIdentité">
        <span class="icon"><i class="fas fa-id-card icon"></i></span>
        <span class="text">Identités visuelles</span>
      </a>
      <a href="shooting.php" class="activeShooting">
        <span class="icon"><i class="fa-solid fa-camera"></i></span>
        <span class="text">Shooting</span>
      </a>
      <?php
      // Afficher le lien Utilisateurs uniquement pour les admins
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
          echo '<a href="utilisateurs.php" class="activeUtilisateurs">';
          echo '<span class="icon"><i class="fas fa-users"></i></span>';
          echo '<span class="text">Utilisateurs</span>';
          echo '</a>';
      }
      ?>
      <a href="logout.php" class="btn-quitter">
        <span class="icon"><i class="fa-solid fa-sign-out-alt"></i></span>
        <span class="text">Déconnexion</span>
      </a>
    </nav>
  </aside>
