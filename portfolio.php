<!DOCTYPE html>
<html lang="fr">
<?php
include 'partials/head.php';

// Récupérer le paramètre de filtre depuis l'URL
$filterParam = isset($_GET['filter']) ? trim($_GET['filter']) : 'all';
$validFilters = ['all', 'sites', 'shooting', 'identite', 'affiches'];
if (!in_array($filterParam, $validFilters)) {
    $filterParam = 'all';
}

// Déterminer quel bouton doit être actif
$activeFilter = $filterParam;
?>
<br>
<br>

<!-- === SECTION BANNIÈRE === -->
<section class="banner-section">
  <div class="banner-container">
    <img src="assets/img/banner-portfolio.png" alt="Votre visibilité notre préoccupation" class="banner-image">
  </div>
</section>

<section class="portfolio-section">
  <div class="portfolio-conteneur">
    <div class="filter-row">
      <div class="filter-buttons">
        <button data-filter="all" class="filter-btn <?php echo $activeFilter === 'all' ? 'active' : ''; ?>">Tout</button>
        <button data-filter="sites" class="filter-btn <?php echo $activeFilter === 'sites' ? 'active' : ''; ?>">Sites</button>
        <button data-filter="shooting" class="filter-btn <?php echo $activeFilter === 'shooting' ? 'active' : ''; ?>">Shooting</button>
        <button data-filter="identite" class="filter-btn <?php echo $activeFilter === 'identite' ? 'active' : ''; ?>">Identité visuelle</button>
        <button data-filter="affiches" class="filter-btn <?php echo $activeFilter === 'affiches' ? 'active' : ''; ?>">Affiches</button>
      </div>
    </div>

    <?php
    // Déterminer le titre selon le filtre actif
    $titles = [
        'all' => 'Notre Portfolio',
        'sites' => 'Nos Sites Web',
        'shooting' => 'Nos Shootings',
        'identite' => 'Nos Identités Visuelles',
        'affiches' => 'Nos Affiches'
    ];
    $pageTitle = $titles[$activeFilter] ?? 'Notre Portfolio';
    ?>
    <h2 class="portfolio-title"><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h2>

    <div class="portfolio-grid">
      <?php
      // Charger tous les éléments du portfolio depuis la base de données
      require_once __DIR__ . '/partials/connect.php';
      
      $allItems = [];
      
      if (isset($connect) && $connect) {
          // Charger les sites
          $querySites = "SELECT *, 'sites' as type FROM sites ORDER BY date_realisation DESC";
          $resultSites = mysqli_query($connect, $querySites);
          if ($resultSites) {
              while ($row = mysqli_fetch_assoc($resultSites)) {
                  $allItems[] = $row;
              }
          }
          
          // Charger les affiches
          $queryAffiches = "SELECT *, 'affiches' as type FROM affiches ORDER BY date_realisation DESC";
          $resultAffiches = mysqli_query($connect, $queryAffiches);
          if ($resultAffiches) {
              while ($row = mysqli_fetch_assoc($resultAffiches)) {
                  $allItems[] = $row;
              }
          }
          
          // Charger les identités visuelles
          $queryIdentites = "SELECT *, 'identite' as type FROM identites ORDER BY date_realisation DESC";
          $resultIdentites = mysqli_query($connect, $queryIdentites);
          if ($resultIdentites) {
              while ($row = mysqli_fetch_assoc($resultIdentites)) {
                  $allItems[] = $row;
              }
          }
          
          // Charger les shootings
          $queryShootings = "SELECT *, 'shooting' as type FROM shootings ORDER BY date_realisation DESC";
          $resultShootings = mysqli_query($connect, $queryShootings);
          if ($resultShootings) {
              while ($row = mysqli_fetch_assoc($resultShootings)) {
                  $allItems[] = $row;
              }
          }
      }
      
      // Afficher tous les éléments
      if (!empty($allItems)) {
          foreach ($allItems as $item) {
              $type = $item['type'] ?? 'sites';
              $imagePath = htmlspecialchars($item['image_path'] ?? '', ENT_QUOTES, 'UTF-8');
              $clientName = htmlspecialchars($item['client_name'] ?? '', ENT_QUOTES, 'UTF-8');
              
              // Normaliser le chemin de l'image
              if (empty($imagePath)) {
                  // Image par défaut selon le type
                  $defaultPaths = [
                      'sites' => '/admin/images/Admin/sites/agri.jpeg',
                      'affiches' => '/admin/images/Admin/affiches/default.jpg',
                      'identite' => '/admin/images/Admin/identités/default.jpg',
                      'shooting' => '/assets/img/logo-acceuil.png'
                  ];
                  $imagePath = $defaultPaths[$type] ?? '/assets/img/logo-acceuil.png';
              } elseif (strpos($imagePath, '/') === 0 && strpos($imagePath, '/admin/') === 0) {
                  // Chemin absolu depuis la racine commençant par /admin/, déjà correct
              } elseif (strpos($imagePath, 'http://') === 0 || strpos($imagePath, 'https://') === 0) {
                  // URL complète, déjà correct
              } elseif (strpos($imagePath, 'admin/images/') === 0) {
                  $imagePath = '/' . $imagePath;
              } elseif (strpos($imagePath, 'images/') === 0) {
                  $imagePath = '/admin/' . $imagePath;
              } else {
                  // Chemin relatif ou nom de fichier seul, construire le chemin complet
                  $typeFolders = [
                      'sites' => 'sites/',
                      'affiches' => 'affiches/',
                      'identite' => 'identités/',
                      'shooting' => 'Shoot/'
                  ];
                  $folder = $typeFolders[$type] ?? 'sites/';
                  
                  // Si c'est juste un nom de fichier (sans slash), construire le chemin complet
                  if (strpos($imagePath, '/') === false && strpos($imagePath, '\\') === false) {
                      $imagePath = '/admin/images/Admin/' . $folder . $imagePath;
                  } else {
                      // Sinon, utiliser le basename et construire le chemin
                      $imagePath = '/admin/images/Admin/' . $folder . basename($imagePath);
                  }
              }
              
              // Vérifier si le fichier existe, sinon utiliser une image par défaut
              $fullPath = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, $imagePath);
              if (!file_exists($fullPath) && strpos($imagePath, '/assets/img/logo-acceuil.png') === false) {
                  // Si l'image n'existe pas, utiliser une image par défaut selon le type
                  $fallbackPaths = [
                      'sites' => '/admin/images/Admin/sites/agri.jpeg',
                      'affiches' => '/admin/images/Admin/affiches/afiche1.jpg',
                      'identite' => '/admin/images/Admin/affiches/Affiche6.jpg',
                      'shooting' => '/admin/images/Admin/affiches/afiche1.jpg' // Utiliser une affiche par défaut pour les shootings
                  ];
                  $imagePath = $fallbackPaths[$type] ?? '/assets/img/logo-acceuil.png';
              }
              // Déterminer si cet élément doit être masqué initialement selon le filtre URL
              $shouldHide = false;
              if ($filterParam !== 'all' && $type !== $filterParam) {
                  $shouldHide = true;
              }
              ?>
              <div class="item <?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?><?php echo $shouldHide ? ' hide' : ''; ?>">
                <img src="<?php echo $imagePath; ?>" 
                     alt="<?php echo $clientName; ?>" 
                     class="portfolio-item-img"
                     onerror="this.src='/assets/img/logo-acceuil.png'; this.onerror=null;">
              </div>
              <?php
          }
      } else {
          // Données par défaut si la BDD n'est pas disponible
          $defaultItems = [
              ['type' => 'sites', 'src' => '/assets/img/afiche1.jpg', 'alt' => 'Site web'],
              ['type' => 'shooting', 'src' => '/assets/img/afiche1.jpg', 'alt' => 'Shooting'],
              ['type' => 'identite', 'src' => '/assets/img/Affiche6.jpg', 'alt' => 'Identité visuelle'],
              ['type' => 'affiches', 'src' => '/assets/img/Affiche6.jpg', 'alt' => 'Affiche'],
              ['type' => 'sites', 'src' => '/assets/img/Affiche5.jpg', 'alt' => 'Site web'],
              ['type' => 'affiches', 'src' => '/assets/img/Affiche6.jpg', 'alt' => 'Affiche']
          ];
          
          foreach ($defaultItems as $defaultItem) {
              $shouldHide = false;
              if ($filterParam !== 'all' && $defaultItem['type'] !== $filterParam) {
                  $shouldHide = true;
              }
              ?>
              <div class="item <?php echo $defaultItem['type']; ?><?php echo $shouldHide ? ' hide' : ''; ?>">
                  <img src="<?php echo $defaultItem['src']; ?>" 
                       alt="<?php echo $defaultItem['alt']; ?>" 
                       class="portfolio-item-img" 
                       onerror="this.src='/assets/img/logo-acceuil.png'; this.onerror=null;">
              </div>
              <?php
          }
      }
      ?>
    </div>
  </div>
</section>

<!-- === MODALE === -->
<div class="modal" id="imageModal">
  <span class="close">&times;</span>
  <img class="modal-image" src="" alt="">
  <button class="prev">&#10094;</button>
  <button class="next">&#10095;</button>
</div>


<?php
include 'partials/footer.php';
?>

<script>
// Script de modale pour portfolio.php
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('imageModal');
    const modalImg = modal ? modal.querySelector('.modal-image') : null;
    const closeBtn = modal ? modal.querySelector('.close') : null;
    const nextBtn = modal ? modal.querySelector('.next') : null;
    const prevBtn = modal ? modal.querySelector('.prev') : null;
    const items = Array.from(document.querySelectorAll('.portfolio-grid .item'));
    
    if (!modal || !modalImg || !closeBtn || !nextBtn || !prevBtn) {
        console.warn('Éléments de la modale introuvables');
        return;
    }
    
    let currentImages = [];
    let currentIndex = 0;
    
    // Fonction pour récupérer les images visibles
    function getVisibleItems() {
        return items.filter(i => {
            const style = window.getComputedStyle(i);
            return style.display !== 'none' && 
                   !i.classList.contains('hide') && 
                   style.visibility !== 'hidden' &&
                   style.opacity !== '0';
        });
    }
    
    // Ouvrir la modale au clic sur une image
    items.forEach((item, index) => {
        const img = item.querySelector('img');
        if (img) {
            item.style.cursor = 'pointer';
            item.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Récupérer toutes les images visibles
                const visibleItems = getVisibleItems();
                
                currentImages = visibleItems.map(i => {
                    const imgEl = i.querySelector('img');
                    return imgEl ? imgEl.src : null;
                }).filter(src => src !== null);
                
                currentIndex = visibleItems.indexOf(item);
                if (currentIndex === -1) currentIndex = 0;
                
                if (currentImages.length > 0) {
                    modal.style.display = 'flex';
                    modalImg.src = currentImages[currentIndex];
                    modalImg.alt = img.alt || '';
                }
            });
        }
    });
    
    // Fermer la modale
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Navigation
    nextBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        // Mettre à jour les images visibles avant de naviguer
        const visibleItems = getVisibleItems();
        currentImages = visibleItems.map(i => {
            const imgEl = i.querySelector('img');
            return imgEl ? imgEl.src : null;
        }).filter(src => src !== null);
        
        if (currentImages.length > 0) {
            currentIndex = (currentIndex + 1) % currentImages.length;
            modalImg.src = currentImages[currentIndex];
        }
    });
    
    prevBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        // Mettre à jour les images visibles avant de naviguer
        const visibleItems = getVisibleItems();
        currentImages = visibleItems.map(i => {
            const imgEl = i.querySelector('img');
            return imgEl ? imgEl.src : null;
        }).filter(src => src !== null);
        
        if (currentImages.length > 0) {
            currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
            modalImg.src = currentImages[currentIndex];
        }
    });
    
    // Navigation au clavier
    document.addEventListener('keydown', function(e) {
        if (modal.style.display === 'flex') {
            if (e.key === 'ArrowLeft') prevBtn.click();
            else if (e.key === 'ArrowRight') nextBtn.click();
            else if (e.key === 'Escape') closeBtn.click();
        }
    });
});
</script>
<!-- Script de filtrage -->
<script src="assets/js/filtrePortfolio.js"></script> 


</body>
</html>