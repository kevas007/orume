<?php
require_once __DIR__ . '/auth.php';
include'adminpartials/head.php'
?>
<!DOCTYPE html>
<html lang="fr">


<body>

 <?php
     include'adminpartials/aside.php'
 ?>
  <!-- Overlay : <div id="overlay" class="overlay"></div>-->
  

  <!-- Main content --> 

  <main class="main-content">
    <header class="topbar">
      <h1 class="titrepage">Identités visuelles</h1>
    </header>

    <!-- SECTION PORTFOLIO -->
    <section class="portfolio">
      <h1 class="portfolio-title">Ajouter une identité visuelle</h1>
 
     
       <!-- AJOUT d'une identité -->
            <!-- section d'ajout d'identité -->
     <div class="portfolio-header">
     
        <form id="identiteForm" class="portfolio-form" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="clientName">Nom du client</label>
            <input type="text" id="clientName" placeholder="Ex: Alpha Group" required>
          </div>

          <div class="form-group">
            <label for="dateRealisation">Date de réalisation</label>
            <input type="month" id="dateRealisation" required>
          </div>

          <div class="form-group">
            <label for="image">Image de l'identité visuelle</label>
            <input type="file" id="image" accept="image/*" required>
          </div>
           <button type="submit" id="addIdentiteBtn" class="btn-submit">
              <i class="fa-solid fa-paper-plane"></i> Enregistrer
           </button>
        </form>
      </div>

        <!-- GRID -->

    <section>
           <h1 class="portfolio-title">Identités visuelles ajoutées</h1>
    </section>
    
    <!-- FILTRES -->
    <section class="filters-section">
      <div class="filters-container">
        <div class="filter-group">
          <label for="filterClient"><i class="fa-solid fa-magnifying-glass"></i> Rechercher par client</label>
          <input type="text" id="filterClient" class="filter-input" placeholder="Nom du client...">
        </div>
        <div class="filter-group">
          <label for="filterDate"><i class="fa-solid fa-calendar"></i> Filtrer par date</label>
          <input type="month" id="filterDate" class="filter-input">
        </div>
        <div class="filter-group">
          <label for="sortOrder"><i class="fa-solid fa-sort"></i> Trier par</label>
          <select id="sortOrder" class="filter-input">
            <option value="desc">Date récente → ancienne</option>
            <option value="asc">Date ancienne → récente</option>
            <option value="name-asc">Nom A → Z</option>
            <option value="name-desc">Nom Z → A</option>
          </select>
        </div>
        <button id="resetFilters" class="btn-reset-filters">
          <i class="fa-solid fa-rotate-left"></i> Réinitialiser
        </button>
      </div>
    </section>
    
    <Section>
      <div class="portfolio-grid" id="portfolioGrid">
        <?php
        // Charger les identités depuis la base de données
        require_once __DIR__ . '/../partials/connect.php';
        
        $identites = [];
        if (isset($connect) && $connect) {
            $query = "SELECT * FROM identites ORDER BY date_realisation DESC";
            $result = mysqli_query($connect, $query);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $identites[] = $row;
                }
            }
        }
        
        // Afficher les identités
        if (!empty($identites)) {
            foreach ($identites as $identite) {
                $id = htmlspecialchars($identite['id'] ?? '', ENT_QUOTES, 'UTF-8');
                $clientName = htmlspecialchars($identite['client_name'] ?? '', ENT_QUOTES, 'UTF-8');
                
                // Gérer la date
                $dateStr = $identite['date_realisation'] ?? '';
                $date = 'N/A';
                if (!empty($dateStr)) {
                    $timestamp = strtotime($dateStr);
                    if ($timestamp !== false) {
                        $date = date('F Y', $timestamp);
                    }
                }
                
                $imagePath = htmlspecialchars($identite['image_path'] ?? '', ENT_QUOTES, 'UTF-8');
                
                // Normaliser le chemin de l'image pour l'admin
                if (!empty($imagePath)) {
                    // Enlever le slash initial s'il existe
                    $imagePath = ltrim($imagePath, '/');
                    
                    // Si le chemin commence par "images/", garder tel quel
                    if (strpos($imagePath, 'images/') === 0) {
                        // Déjà correct
                    } 
                    // Si le chemin commence par "admin/images/", enlever "admin/"
                    elseif (strpos($imagePath, 'admin/images/') === 0) {
                        $imagePath = str_replace('admin/images/', 'images/', $imagePath);
                    }
                    // Sinon, supposer que c'est un chemin relatif depuis la racine admin
                    else {
                        $imagePath = 'images/Admin/identités/' . basename($imagePath);
                    }
                    
                    // Vérifier si le fichier existe réellement
                    $fullPath = __DIR__ . '/' . $imagePath;
                    if (!file_exists($fullPath)) {
                        // Si l'image n'existe pas, utiliser une image par défaut
                        $imagePath = 'images/Admin/affiches/Affiche6.jpg'; // Utiliser une affiche par défaut
                    }
                } else {
                    $imagePath = 'images/Admin/affiches/Affiche6.jpg'; // Image par défaut
                }
                ?>
                <div class="portfolio-card" data-id="<?php echo $id; ?>">
                  <img src="<?php echo $imagePath; ?>" 
                       alt="Identité visuelle <?php echo $clientName; ?>" 
                       class="portfolio-img"
                       onerror="this.src='images/Admin/affiches/Affiche6.jpg'; this.onerror=null;">
                  <div class="portfolio-info">
                    <h3>Client : <?php echo $clientName; ?></h3>
                    <p><i class="fa-solid fa-calendar"></i> Date : <?php echo $date; ?></p>

                    <!-- Actions -->
                    <div class="portfolio-actions">
                      <button class="btn-edit" data-id="<?php echo $id; ?>"><i class="fa-solid fa-pen-to-square"></i> Modifier</button>
                      <button class="btn-delete" data-id="<?php echo $id; ?>"><i class="fa-solid fa-trash"></i> Supprimer</button>
                    </div>
                  </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">
              <p style="font-size: 18px;">Aucune identité visuelle ajoutée pour le moment.</p>
              <p style="font-size: 14px; margin-top: 10px;">Utilisez le formulaire ci-dessus pour ajouter votre première identité visuelle.</p>
            </div>
            <?php
        }
        ?>
      </div>

   </section>
  </main>

  <!-- POPUP IMAGE -->
  <div id="imgModal" class="img-modal">
    <span class="close-modal">&times;</span>
    <img class="modal-content" id="imgPreview">
    <div id="caption"></div>
  </div>

  <!-- formulaire DE MODIFICATION -->
<div id="editModal" class="edit-modal">
  <div class="edit-modal-content">
    <span class="close-edit">&times;</span>
    <h2>Modifier l'identité visuelle</h2>

    <form id="editForm">
      <div class="form-group">
        <label for="editClientName">Nom du client</label>
        <input type="text" id="editClientName" required>
      </div>

      <div class="form-group">
        <label for="editDate">Date de réalisation</label>
        <input type="month" id="editDate" required>
      </div>

      <div class="form-group">
        <label for="editImage">Changer l'image (optionnel)</label>
        <input type="file" id="editImage" accept="image/*">
      </div>

      <button type="submit" class="btn-edit-save">
        <i class="fa-solid fa-floppy-disk"></i> Enregistrer les modifications
      </button>
    </form>
  </div>
</div>

   <script>
    // ---------- ZOOM IMAGE ----------
    const modal = document.getElementById("imgModal");
    const modalImg = document.getElementById("imgPreview");
    const captionText = document.getElementById("caption");
    const span = document.getElementsByClassName("close-modal")[0];

    document.querySelectorAll(".portfolio-img").forEach(img => {
      img.onclick = function() {
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
      };
    });

    span.onclick = function() {
      modal.style.display = "none";
    };
  </script> 
  
  <script src="js/script.js"></script>
   <script src="js/Ajout_identite.js"></script>
   <script src="js/sibebar.js"></script>
   <script src="js/active_sidebar.js"></script>
  <script src="js/Supprimer_Info.js"></script>
  <script src="js/Modifier_identite.js"></script>
  <script src="js/Filtre_portfolio.js"></script>

</body>
</html>

