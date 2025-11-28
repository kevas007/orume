<?php
/**
 * ============================================
 * PAGE D'ACCUEIL - FRONTEND PUBLIC
 * ============================================
 * 
 * Cette page est la page principale du site vitrine Orüme.
 * Elle présente l'agence, ses services, ses partenaires et
 * un aperçu du portfolio.
 * 
 * @package Orüme
 * @version 1.0.0
 */

// Charger le bootstrap de l'application (optionnel pour cette page statique)
// Désactivé pour éviter les erreurs sur cette page statique
// if (file_exists(__DIR__ . '/bootstrap.php')) {
//     require_once __DIR__ . '/bootstrap.php';
// }
?>
<!DOCTYPE html>
<html lang="fr">
<?php include 'partials/head.php'; ?>

<!-- HERO CONTENT avec seulement une image -->
<div class="hero-content">
    <img src="assets/img/background.png" alt="Orüme Hero" class="hero-img">
</div>
</header>
   <br>
   
  <section class="about">
  <div class="about-container">
    
    <!-- Texte gauche -->

    <div class="about-text">
      <p class="hello">Bonjour!</p>
      <h2><span class="highlight">Orüme</span> est une <br>
        <strong>Creative Agency of Digital Based in <span class="green">TOGO.</span></strong>
      </h2>
      <p class="description">
        Nous sommes une agence créative expérimentée de solutions numériques avec 4 ans dans le domaine collaborant avec diverses entreprises et startups.
      </p>
      
      <div class="cta-buttons">
        <a href="#" class="btn-primary">Voir notre portfolio</a>
        <a href="#" class="btn-secondary">Nous joindre</a>
      </div>
    </div>

    <!-- Image droite -->
    <div class="about-image">
      <!--<div class="bg-shape"></div>-->
      <img src="assets/img/homme.png" alt="Fondateur Orüme" class="person-img">
      <!--<span class="badge badge1">Designer UX/UI</span>
      <span class="badge badge2">Designer Produit</span>-->
    </div>
  </div>

  <!-- Bandeau Qualités -->
  <div class="qualities">
  <span>Flexibilité</span>
  <span class="star">*</span>
  <span>Créativité</span>
  <span class="star">*</span>
  <span>Professionalisme</span>
  <span class="star">*</span>
  <span>Rigueur</span>
</div>
</section>


<section class="services" id="services">
  <div class="services-header">
    <div class="services-title">
      <span class="subtitle">Services</span>
      <h2>que nous <span>offrons</span></h2>
    </div>
    <a href="services.php" class="btn-services">Nos services</a>
  </div>

  <div class="services-grid">
    <div class="service-card">
      <img src="assets/img/1.png" alt="UX/UI" class="service-icon">
      <h3>Design UX/UI</h3>
      <p>Consultant en UX/UI sur tout projets de développement. 
         Nous offrons une expertise et une rigueur dans nos démarches.</p>
    </div>

    <div class="service-card">
      <img src="assets/img/2.png" alt="Design Graphique" class="service-icon">
      <h3>Design Graphique</h3>
      <p>Expert en communication visuelle, nous sommes dans la production 
         de créas publicitaires colorés, simples et attrayants.</p>
    </div>

    <div class="service-card">
      <img src="assets/img/3.png" alt="Identité Visuelle" class="service-icon">
      <h3>Identité Visuelle</h3>
      <p>Création et gestion d’image de marque, nous donnons une 
         personnalité unique à nos partenaires à travers leur image.</p>
    </div>

    <div class="service-card">
      <img src="assets/img/4.png" alt="Site Vitrine" class="service-icon">
      <h3>Site Vitrine</h3>
      <p>Consultant en UX/UI sur tout projets de développement. 
         Nous offrons une expertise et une rigueur dans nos démarches.</p>
    </div>

    <div class="service-card">
      <img src="assets/img/5.png" alt="Référencement" class="service-icon">
      <h3>Référencement</h3>
      <p>Expert en communication visuelle, nous sommes dans la production 
         de créas publicitaires colorés, simples et attrayants.</p>
    </div>

    <div class="service-card">
      <img src="assets/img/6.png" alt="Shooting Produit" class="service-icon">
      <h3>Shooting Produit</h3>
      <p>Création et gestion d’image de marque, nous donnons une 
         personnalité unique à nos partenaires à travers leur image.</p>
    </div>
  </div>
</section>

<br>
<br>
<br>
<br>
<br>

<section class="partenaires" id="partenaires">
   <!-- Bandeau orange -->
  <div class="partenaires-header">
    <div class="partenaires-title">
      <h2>PARTENAIRES</h2>
    </div>
  </div>

  <!-- Logos -->
  <div class="partenaires-grid">
    <img src="assets/img/a1.png" alt="Paula Cake">
    <img src="assets/img/a2.png" alt="Metanoia">
    <img src="assets/img/a3.png" alt="X-Great">
    <img src="assets/img/a4.png" alt="Délice de Sika">

    <img src="assets/img/a5.png" alt="Okoro">
    <img src="assets/img/a6.png" alt="Atelier des Saveurs">
    <img src="assets/img/a7.png" alt="Délices de Navi">
    <img src="assets/img/a8.png" alt="Interior Design">
  </div>
</section>




<section id="portfolio" class="portfolio">
  <div class="portfolio-container">
    <h3> Nos Réalisations</h3>

    <div class="portfolio-grid">
      <div class="portfolio-item">
        <div class="card">
          <img src="assets/img/afiche1.jpg" alt="Affiche 1">
        </div>
      </div>
      <div class="portfolio-item">
        <div class="card">
          <img src="assets/img/Affiche6.jpg" alt="Affiche 6">
        </div>
      </div>
      <div class="portfolio-item">
        <div class="card">
          <img src="assets/img/Affiche7.jpg" alt="Affiche 7">
        </div>
      </div>
      <div class="portfolio-item">
        <div class="card">
          <img src="assets/img/FLIER.jpg" alt="Flyer">
        </div>
      </div>
      <div class="portfolio-item">
        <div class="card">
          <img src="assets/img/Affiche5.jpg" alt="Affiche 5">
        </div>
      </div>
      <div class="portfolio-item">
        <div class="card">
          <img src="assets/img/AficheBtp2.jpg" alt="Affiche BTP">
        </div>
      </div>
    </div>
<br>
<br>
    <div class="gta-buttons">
      <a href="portfolio.html" class="btn-primary">Voir plus</a>
    </div>
  </div>
</section>

<div class="lightbox" id="lightbox">
  <span class="close">&times;</span>
  <span class="prev">&#10094;</span>
  <img id="lightbox-img" src="" alt="Preview">
  <span class="next">&#10095;</span>
</div>



 <?php
      include'partials/footer.php'
  ?>

</body>
</html>
