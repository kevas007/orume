<?php
require_once __DIR__ . '/auth.php';
include 'adminpartials/head.php';

// Charger les statistiques depuis la base de données
require_once __DIR__ . '/../partials/connect.php';

$totalMessages = 0;
$totalSites = 0;
$totalAffiches = 0;
$totalIdentites = 0;
$nonLusMessages = 0;

// Calculer les pourcentages de croissance (30 derniers jours)
$date30Jours = date('Y-m-d', strtotime('-30 days'));
$date60Jours = date('Y-m-d', strtotime('-60 days'));

if (isset($connect) && $connect) {
    // Messages
    $queryMessages = "SELECT COUNT(*) as total FROM messages";
    $resultMessages = mysqli_query($connect, $queryMessages);
    if ($resultMessages) {
        $row = mysqli_fetch_assoc($resultMessages);
        $totalMessages = $row['total'];
    }
    
    $queryMessages30 = "SELECT COUNT(*) as total FROM messages WHERE created_at >= '$date30Jours'";
    $resultMessages30 = mysqli_query($connect, $queryMessages30);
    $messages30 = $resultMessages30 ? mysqli_fetch_assoc($resultMessages30)['total'] : 0;
    $queryMessages60 = "SELECT COUNT(*) as total FROM messages WHERE created_at >= '$date60Jours' AND created_at < '$date30Jours'";
    $resultMessages60 = mysqli_query($connect, $queryMessages60);
    $messages60 = $resultMessages60 ? mysqli_fetch_assoc($resultMessages60)['total'] : 0;
    $messagesAvant = max(1, $messages60);
    if ($messagesAvant > 0 && $messages60 > 0) {
        $pourcentMessages = round((($messages30 - $messages60) / $messagesAvant) * 100);
    } else {
        $pourcentMessages = $messages30 > 0 ? 100 : 0;
    }
    
    // Messages non lus
    $queryNonLus = "SELECT COUNT(*) as total FROM messages WHERE statut = 'non_lu'";
    $resultNonLus = mysqli_query($connect, $queryNonLus);
    if ($resultNonLus) {
        $row = mysqli_fetch_assoc($resultNonLus);
        $nonLusMessages = $row['total'];
    }
    
    // Sites
    $querySites = "SELECT COUNT(*) as total FROM sites";
    $resultSites = mysqli_query($connect, $querySites);
    if ($resultSites) {
        $row = mysqli_fetch_assoc($resultSites);
        $totalSites = $row['total'];
    }
    
    $querySites30 = "SELECT COUNT(*) as total FROM sites WHERE created_at >= '$date30Jours'";
    $resultSites30 = mysqli_query($connect, $querySites30);
    $sites30 = $resultSites30 ? mysqli_fetch_assoc($resultSites30)['total'] : 0;
    $querySites60 = "SELECT COUNT(*) as total FROM sites WHERE created_at >= '$date60Jours' AND created_at < '$date30Jours'";
    $resultSites60 = mysqli_query($connect, $querySites60);
    $sites60 = $resultSites60 ? mysqli_fetch_assoc($resultSites60)['total'] : 0;
    $sitesAvant = max(1, $sites60);
    if ($sitesAvant > 0 && $sites60 > 0) {
        $pourcentSites = round((($sites30 - $sites60) / $sitesAvant) * 100);
    } else {
        $pourcentSites = $sites30 > 0 ? 100 : 0;
    }
    
    // Affiches
    $queryAffiches = "SELECT COUNT(*) as total FROM affiches";
    $resultAffiches = mysqli_query($connect, $queryAffiches);
    if ($resultAffiches) {
        $row = mysqli_fetch_assoc($resultAffiches);
        $totalAffiches = $row['total'];
    }
    
    $queryAffiches30 = "SELECT COUNT(*) as total FROM affiches WHERE created_at >= '$date30Jours'";
    $resultAffiches30 = mysqli_query($connect, $queryAffiches30);
    $affiches30 = $resultAffiches30 ? mysqli_fetch_assoc($resultAffiches30)['total'] : 0;
    $queryAffiches60 = "SELECT COUNT(*) as total FROM affiches WHERE created_at >= '$date60Jours' AND created_at < '$date30Jours'";
    $resultAffiches60 = mysqli_query($connect, $queryAffiches60);
    $affiches60 = $resultAffiches60 ? mysqli_fetch_assoc($resultAffiches60)['total'] : 0;
    $affichesAvant = max(1, $affiches60);
    if ($affichesAvant > 0 && $affiches60 > 0) {
        $pourcentAffiches = round((($affiches30 - $affiches60) / $affichesAvant) * 100);
    } else {
        $pourcentAffiches = $affiches30 > 0 ? 100 : 0;
    }
    
    // Identités
    $queryIdentites = "SELECT COUNT(*) as total FROM identites";
    $resultIdentites = mysqli_query($connect, $queryIdentites);
    if ($resultIdentites) {
        $row = mysqli_fetch_assoc($resultIdentites);
        $totalIdentites = $row['total'];
    }
    
    $queryIdentites30 = "SELECT COUNT(*) as total FROM identites WHERE created_at >= '$date30Jours'";
    $resultIdentites30 = mysqli_query($connect, $queryIdentites30);
    $identites30 = $resultIdentites30 ? mysqli_fetch_assoc($resultIdentites30)['total'] : 0;
    $queryIdentites60 = "SELECT COUNT(*) as total FROM identites WHERE created_at >= '$date60Jours' AND created_at < '$date30Jours'";
    $resultIdentites60 = mysqli_query($connect, $queryIdentites60);
    $identites60 = $resultIdentites60 ? mysqli_fetch_assoc($resultIdentites60)['total'] : 0;
    $identitesAvant = max(1, $identites60);
    if ($identitesAvant > 0 && $identites60 > 0) {
        $pourcentIdentites = round((($identites30 - $identites60) / $identitesAvant) * 100);
    } else {
        $pourcentIdentites = $identites30 > 0 ? 100 : 0;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OÜme Admin Dashboard</title>
  
  <!-- Librairie Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php
  include 'adminpartials/aside.php';
?>

  <!-- Main -->
  <main class="main-content">
    <!-- Topbar -->
    <header class="topbar">
      <h1 class="titrePage">Acceuil</h1>
    </header>

    <!-- Welcome Banner -->
    <section class="welcome-banner">
      <div class="welcome-content">
        <div class="welcome-text">
          <h1>BIENVENUE Admin Orume</h1>
          <p>Tous les systèmes fonctionnent bien. Vous avez <?php echo $nonLusMessages; ?> alertes non lues !</p>
        </div>
        <div class="weather-info">
          <div class="weather-temp">31°C</div>
          <div class="weather-location">Lomé, Togo</div>
        </div>
      </div>
    </section>

    <!-- KPI Cards -->
    <section class="kpi-cards">
      <div class="kpi-card">
        <div class="kpi-bar green"></div>
        <div class="kpi-content">
          <h3>Messages reçus</h3>
          <div class="kpi-value"><?php echo $totalMessages; ?></div>
          <div class="kpi-trend <?php echo $pourcentMessages >= 0 ? 'positive' : 'negative'; ?>"><?php echo $pourcentMessages >= 0 ? '+' : ''; ?><?php echo $pourcentMessages; ?>% (30 jours)</div>
        </div>
      </div>
      
      <div class="kpi-card">
        <div class="kpi-bar orange"></div>
        <div class="kpi-content">
          <h3>Portfolios réalisés</h3>
          <div class="kpi-value"><?php echo $totalSites; ?></div>
          <div class="kpi-trend <?php echo $pourcentSites >= 0 ? 'positive' : 'negative'; ?>"><?php echo $pourcentSites >= 0 ? '+' : ''; ?><?php echo $pourcentSites; ?>% (30 jours)</div>
        </div>
      </div>
      
      <div class="kpi-card">
        <div class="kpi-bar green"></div>
        <div class="kpi-content">
          <h3>Affiches créées</h3>
          <div class="kpi-value"><?php echo $totalAffiches; ?></div>
          <div class="kpi-trend <?php echo $pourcentAffiches >= 0 ? 'positive' : 'negative'; ?>"><?php echo $pourcentAffiches >= 0 ? '+' : ''; ?><?php echo $pourcentAffiches; ?>% (30 jours)</div>
        </div>
      </div>
      
      <div class="kpi-card">
        <div class="kpi-bar orange"></div>
        <div class="kpi-content">
          <h3>Identités visuelles</h3>
          <div class="kpi-value"><?php echo $totalIdentites; ?></div>
          <div class="kpi-trend <?php echo $pourcentIdentites >= 0 ? 'positive' : 'negative'; ?>"><?php echo $pourcentIdentites >= 0 ? '+' : ''; ?><?php echo $pourcentIdentites; ?>% (30 jours)</div>
        </div>
      </div>
    </section>

    <!-- Info Cards -->
    <section class="info-cards">
      <div class="info-card">
        <h3>Détails des commandes</h3>
        <p>Nombre total de sessions actives dans la période choisie.</p>
      </div>
      
      <div class="info-card">
        <h3>Rapport des ventes</h3>
        <p>Nombre total de conversions dans la période sélectionnée.</p>
        <a href="rapport-ventes.php" class="info-link">Voir plus</a>
      </div>
    </section>
  </main>

  <script src="js/sibebar.js"></script>
  <script src="js/active_sidebar.js"></script>

</body>
</html>
