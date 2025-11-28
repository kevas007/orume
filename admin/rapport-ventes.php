<?php
require_once __DIR__ . '/auth.php';
include 'adminpartials/head.php';

// Charger les statistiques depuis la base de données
require_once __DIR__ . '/../partials/connect.php';

$totalConversions = 0;
$conversions30Jours = 0;
$conversions60Jours = 0;
$pourcentConversions = 0;

$date30Jours = date('Y-m-d', strtotime('-30 days'));
$date60Jours = date('Y-m-d', strtotime('-60 days'));

if (isset($connect) && $connect) {
    // Les conversions peuvent être représentées par les messages convertis en commandes
    // ou par les projets réalisés. Pour l'instant, on utilise les messages avec statut "repondu"
    $queryConversions = "SELECT COUNT(*) as total FROM messages WHERE statut = 'repondu'";
    $resultConversions = mysqli_query($connect, $queryConversions);
    if ($resultConversions) {
        $row = mysqli_fetch_assoc($resultConversions);
        $totalConversions = $row['total'];
    }
    
    $queryConversions30 = "SELECT COUNT(*) as total FROM messages WHERE statut = 'repondu' AND created_at >= '$date30Jours'";
    $resultConversions30 = mysqli_query($connect, $queryConversions30);
    $conversions30Jours = $resultConversions30 ? mysqli_fetch_assoc($resultConversions30)['total'] : 0;
    
    $queryConversions60 = "SELECT COUNT(*) as total FROM messages WHERE statut = 'repondu' AND created_at >= '$date60Jours' AND created_at < '$date30Jours'";
    $resultConversions60 = mysqli_query($connect, $queryConversions60);
    $conversions60Jours = $resultConversions60 ? mysqli_fetch_assoc($resultConversions60)['total'] : 0;
    
    $conversionsAvant = max(1, $conversions60Jours);
    if ($conversionsAvant > 0 && $conversions60Jours > 0) {
        $pourcentConversions = round((($conversions30Jours - $conversions60Jours) / $conversionsAvant) * 100);
    } else {
        $pourcentConversions = $conversions30Jours > 0 ? 100 : 0;
    }
    
    // Récupérer les conversions détaillées
    $queryDetails = "SELECT m.*, c.nom as client_nom FROM messages m 
                     LEFT JOIN clients c ON m.client_id = c.id 
                     WHERE m.statut = 'repondu' 
                     ORDER BY m.created_at DESC 
                     LIMIT 50";
    $resultDetails = mysqli_query($connect, $queryDetails);
    $conversionsDetails = [];
    if ($resultDetails) {
        while ($row = mysqli_fetch_assoc($resultDetails)) {
            $conversionsDetails[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rapport des ventes - Orüme Admin</title>
</head>
<body>

<?php
  include 'adminpartials/aside.php';
?>

  <!-- Main -->
  <main class="main-content">
    <!-- Topbar -->
    <header class="topbar">
      <h1>Rapport des ventes</h1>
      <a href="index.php" class="btn-back">
        <i class="fas fa-arrow-left"></i> Retour
      </a>
    </header>

    <!-- Statistiques -->
    <section class="stats-section">
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-content">
          <h3>Total conversions</h3>
          <div class="stat-value"><?php echo $totalConversions; ?></div>
          <div class="stat-trend <?php echo $pourcentConversions >= 0 ? 'positive' : 'negative'; ?>">
            <?php echo $pourcentConversions >= 0 ? '+' : ''; ?><?php echo $pourcentConversions; ?>% (30 jours)
          </div>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-content">
          <h3>Conversions (30 jours)</h3>
          <div class="stat-value"><?php echo $conversions30Jours; ?></div>
          <div class="stat-label">Derniers 30 jours</div>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-history"></i>
        </div>
        <div class="stat-content">
          <h3>Période précédente</h3>
          <div class="stat-value"><?php echo $conversions60Jours; ?></div>
          <div class="stat-label">30-60 jours</div>
        </div>
      </div>
    </section>

    <!-- Détails des conversions -->
    <section class="conversions-list">
      <h2>Détails des conversions</h2>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Client</th>
              <th>Email</th>
              <th>Sujet</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (!empty($conversionsDetails)) {
                foreach ($conversionsDetails as $conversion) {
                    $date = date('d/m/Y H:i', strtotime($conversion['created_at']));
                    $nom = htmlspecialchars($conversion['nom'] ?? '', ENT_QUOTES, 'UTF-8');
                    $email = htmlspecialchars($conversion['email'] ?? '', ENT_QUOTES, 'UTF-8');
                    $sujet = htmlspecialchars($conversion['sujet'] ?? '', ENT_QUOTES, 'UTF-8');
                    $statut = htmlspecialchars($conversion['statut'] ?? '', ENT_QUOTES, 'UTF-8');
                    
                    echo "<tr>";
                    echo "<td>{$date}</td>";
                    echo "<td>{$nom}</td>";
                    echo "<td>{$email}</td>";
                    echo "<td>{$sujet}</td>";
                    echo "<td><span class='badge badge-repondu'>{$statut}</span></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align: center; padding: 20px;'>Aucune conversion trouvée</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <script src="js/sibebar.js"></script>
  <script src="js/active_sidebar.js"></script>

  <style>
    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      background: #05382c;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 500;
      transition: background 0.3s;
    }
    
    .btn-back:hover {
      background: #1e8b5a;
    }
    
    .stats-section {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }
    
    .stat-card {
      background: #fff;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      gap: 20px;
    }
    
    .stat-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #05382c, #1e8b5a);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 1.5em;
    }
    
    .stat-content h3 {
      font-size: 0.9em;
      color: #666;
      margin: 0 0 8px 0;
      font-weight: 500;
    }
    
    .stat-value {
      font-size: 2em;
      font-weight: 700;
      color: #05382c;
      margin: 0 0 5px 0;
    }
    
    .stat-label {
      font-size: 0.85em;
      color: #999;
    }
    
    .stat-trend {
      font-size: 0.85em;
      font-weight: 600;
    }
    
    .stat-trend.positive {
      color: #1e8b5a;
    }
    
    .stat-trend.negative {
      color: #dc3545;
    }
    
    .conversions-list {
      background: #fff;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .conversions-list h2 {
      color: #05382c;
      margin: 0 0 20px 0;
      font-size: 1.5em;
    }
    
    .table-container {
      overflow-x: auto;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
    }
    
    table thead {
      background: #f8f9fa;
    }
    
    table th {
      padding: 15px;
      text-align: left;
      color: #05382c;
      font-weight: 600;
      border-bottom: 2px solid #e0e0e0;
    }
    
    table td {
      padding: 15px;
      border-bottom: 1px solid #e0e0e0;
    }
    
    table tbody tr:hover {
      background: #f8f9fa;
    }
    
    .badge {
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85em;
      font-weight: 600;
      display: inline-block;
    }
    
    .badge-repondu {
      background: #1e8b5a;
      color: #fff;
    }
    
    @media (max-width: 1024px) {
      .stats-section {
        grid-template-columns: 1fr;
      }
    }
  </style>

</body>
</html>

