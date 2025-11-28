
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orüme - Accueil</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<script src="assets/js/main.js"></script>



<?php
  $bodyClassAttr = '';
  if (isset($pageClass) && $pageClass !== '') {
    $bodyClassAttr = ' class="' . htmlspecialchars($pageClass, ENT_QUOTES, 'UTF-8') . '"';
  }
?>
<body<?php echo $bodyClassAttr; ?>>
  
  <!-- ===== HEADER + HERO ===== -->
  <header class="hero">
    <!-- NAVIGATION -->
    <nav class="navbar">
      <div class="logo">
        <a href="#"><img src="assets/img/logo-acceuil.png" alt="Orüme Logo" class="logo-img"></a>
      </div>
      <ul class="nav-links" style="margin-right: 180px;">
        <li><a href="acceuil.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'acceuil.php') ? 'class="active"' : ''; ?>>Accueil</a></li>
        <li><a href="#">Nous</a></li>
        <li><a href="services.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'services.php') ? 'class="active"' : ''; ?>>Services</a></li>
        <li class="dropdown">
      <a href="portfolio.php" <?php echo (strpos(basename($_SERVER['PHP_SELF']), 'portfolio') !== false) ? 'class="active"' : ''; ?>>Portfolio ▼</a>
      <ul class="dropdown-menu">
        <li><a href="portfolio.php?filter=sites">Sites</a></li>
        <li><a href="portfolio.php?filter=affiches">Affiches</a></li>
        <li><a href="portfolio.php?filter=identite">Identité visuelle</a></li>
        <li><a href="portfolio.php?filter=shooting">Shooting</a></li>
      </ul>
    </li>
        <li><a href="contact.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'class="active"' : ''; ?>>Contact</a></li>
      </ul>
    </nav>