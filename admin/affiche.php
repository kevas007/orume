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

  <!-- Contenu principal -->
  <main class="main-content">
    <header class="topbar">
      <h1 class="titrepage">Affiches</h1>
    </header>

    <section class="portfolio">
      <h1 class="portfolio-title">Ajouter une Affiche</h1>

      <!-- FORMULAIRE D’AJOUT -->
      <div class="portfolio-header">
        <form id="afficheForm" class="portfolio-form" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="clientAffiche">Nom du client</label>
            <input type="text" id="clientAffiche" placeholder="Ex: Maison Luxe" required>
          </div>

          <div class="form-group">
            <label for="dateAffiche">Date de réalisation</label>
            <input type="month" id="dateAffiche" required>
          </div>

          <div class="form-group">
            <label for="imageAffiche">Image de l’affiche</label>
            <input type="file" id="imageAffiche" accept="image/*" required>
          </div>

          <button type="submit" id="addAfficheBtn" class="btn-submit">
            <i class="fa-solid fa-paper-plane"></i> Enregistrer
          </button>
        </form>
      </div>

      <!-- SECTION AFFICHES AJOUTÉES -->
      <section>
        <h1 class="portfolio-title">Affiches Réalisées</h1>
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

      <!-- CARTES D'AFFICHES -->
      <div class="portfolio-grid" id="portfolioGrid">
        <?php
        // Charger les affiches depuis la base de données
        require_once __DIR__ . '/../partials/connect.php';
        
        $affiches = [];
        if (isset($connect) && $connect) {
            $query = "SELECT * FROM affiches ORDER BY date_realisation DESC";
            $result = mysqli_query($connect, $query);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $affiches[] = $row;
                }
            }
        }
        
        // Afficher les affiches
        if (!empty($affiches)) {
            foreach ($affiches as $affiche) {
                $id = htmlspecialchars($affiche['id'], ENT_QUOTES, 'UTF-8');
                $clientName = htmlspecialchars($affiche['client_name'], ENT_QUOTES, 'UTF-8');
                $date = date('F Y', strtotime($affiche['date_realisation']));
                $imagePath = htmlspecialchars($affiche['image_path'], ENT_QUOTES, 'UTF-8');
                
                // Normaliser le chemin de l'image
                if (strpos($imagePath, 'images/') === 0) {
                    $imagePath = $imagePath;
                } elseif (strpos($imagePath, 'admin/images/') === 0) {
                    $imagePath = str_replace('admin/images/', 'images/', $imagePath);
                }
                
                // Vérifier si l'image existe
                $fullImagePath = __DIR__ . '/../' . $imagePath;
                $imageExists = file_exists($fullImagePath);
                
                if (!$imageExists) {
                    // Essayer de trouver l'image dans assets/img
                    $imageName = basename($imagePath);
                    $assetsPath = __DIR__ . '/../../assets/img/' . $imageName;
                    if (file_exists($assetsPath)) {
                        $imagePath = 'assets/img/' . $imageName;
                        $imageExists = true;
                    } else {
                        // Essayer dans admin/images/Admin/affiches/
                        $adminPath = __DIR__ . '/images/Admin/affiches/' . $imageName;
                        if (file_exists($adminPath)) {
                            $imagePath = 'images/Admin/affiches/' . $imageName;
                            $imageExists = true;
                        }
                    }
                }
                ?>
                <div class="portfolio-card" data-id="<?php echo $id; ?>">
                  <?php if ($imageExists): ?>
                    <img src="<?php echo $imagePath; ?>" alt="Affiche <?php echo $clientName; ?>" class="portfolio-img">
                  <?php else: ?>
                    <div style="width:100%; height:200px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; color:#999;">
                      <div style="text-align:center;">
                        <i class="fas fa-image" style="font-size:3em; margin-bottom:10px; display:block;"></i>
                        <p>Image non disponible</p>
                      </div>
                    </div>
                  <?php endif; ?>
                  <div class="portfolio-info">
                    <h3>Client : <?php echo $clientName; ?></h3>
                    <p><i class="fa-solid fa-calendar"></i> Date : <?php echo $date; ?></p>
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
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
              <p>Aucune affiche ajoutée pour le moment.</p>
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


  <!-- MODAL DE MODIFICATION -->
  <div id="editModalAffiche" class="edit-modal">
    <div class="edit-modal-content">
      <span class="close-edit">&times;</span>
      <h2>Modifier une Affiche</h2>

      <form id="editAfficheForm">
        <div class="form-group">
          <label for="editClientAffiche">Nom du client</label>
          <input type="text" id="editClientAffiche" required>
        </div>

        <div class="form-group">
          <label for="editDateAffiche">Date de réalisation</label>
          <input type="month" id="editDateAffiche" required>
        </div>

        <!-- Les affiches n'ont pas de champ email/contact -->

        <div class="form-group">
          <label for="editImageAffiche">Changer l’image (optionnel)</label>
          <input type="file" id="editImageAffiche" accept="image/*">
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
  
  <!-- SCRIPTS -->
  <script src="js/sibebar.js"></script>
   <script src="js/active_sidebar.js"></script>
  <script src="js/Ajout.js"></script>
  <script src="js/Modifier_info_affiche.js"></script>
  <script src="js/Supprimer_affiche.js"></script>
  <script src="js/Filtre_portfolio.js"></script>
</body>
</html>
