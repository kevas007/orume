// Ce script est remplacé par sibebar.js pour toutes les pages admin
// Il est conservé uniquement pour la compatibilité avec d'anciennes pages
// qui n'utilisent pas encore sibebar.js
document.addEventListener("DOMContentLoaded", () => {
  // Vérifier si sibebar.js est déjà chargé (il gère la sidebar)
  // Si sibebar.js est présent, ne pas exécuter ce script pour éviter les conflits
  const isSibebarPage = window.location.pathname.includes('portfolio.php') ||
                        window.location.pathname.includes('affiche.php') ||
                        window.location.pathname.includes('identites.php') ||
                        window.location.pathname.includes('shooting.php') ||
                        window.location.pathname.includes('Messages.php') ||
                        window.location.pathname.includes('index.php');
  
  // Si c'est une page qui utilise sibebar.js, ne pas exécuter ce script
  if (isSibebarPage) {
    return;
  }

  const sidebar = document.getElementById("sidebar");
  const hamburger = document.getElementById("hamburger");
  const hamburgerTop = document.getElementById("hamburger-top");
  const overlay = document.getElementById("overlay");

  // Vérifier que les éléments essentiels existent
  if (!sidebar || !hamburger) {
    console.warn("Éléments de la sidebar introuvables");
    return;
  }

  // Toggle sidebar (both buttons)
  function toggleSidebar() {
   if (window.innerWidth <= 768) {
      sidebar.classList.toggle("open");
      if (overlay) {
        overlay.classList.toggle("active");
      }
    } else {
      sidebar.classList.toggle("collapsed");
    }
  }

  hamburger.addEventListener("click", toggleSidebar);
  
  if (overlay) {
    overlay.addEventListener("click", () => {
      sidebar.classList.remove("open");
      overlay.classList.remove("active");
    });
  }
  
  if (hamburgerTop) {
    hamburgerTop.addEventListener("click", toggleSidebar);
  }
});
