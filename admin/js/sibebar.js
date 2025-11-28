// ======== TOGGLE SIDEBAR ========
document.addEventListener("DOMContentLoaded", function() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");
  const hamburger = document.getElementById("hamburger");
  const hamburgerTop = document.getElementById("hamburger-top");
  const mainContent = document.querySelector(".main-content");

  // Vérifier que la sidebar existe
  if (!sidebar) {
    return;
  }

  // Fonction pour basculer la sidebar
  function toggleSidebar(e) {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    
    sidebar.classList.toggle("collapsed");
    
    const isCollapsed = sidebar.classList.contains("collapsed");
    
    // Gérer l'overlay si il existe (pour mobile)
    if (overlay) {
      if (isCollapsed) {
        overlay.classList.add("active");
      } else {
        overlay.classList.remove("active");
      }
    }
    
    // Ajuster le margin-left du main-content
    if (mainContent) {
      if (isCollapsed) {
        mainContent.style.marginLeft = "70px";
      } else {
        mainContent.style.marginLeft = "220px";
      }
    }
  }

  // Clic sur le hamburger (dans la sidebar)
  if (hamburger) {
    hamburger.addEventListener("click", toggleSidebar);
    // Également gérer le clic sur l'icône à l'intérieur pour éviter les problèmes
    const hamburgerIcon = hamburger.querySelector("i");
    if (hamburgerIcon) {
      hamburgerIcon.addEventListener("click", function(e) {
        e.stopPropagation();
        toggleSidebar(e);
      });
    }
  }
  
  // Clic sur le hamburger dans le topbar (si présent)
  if (hamburgerTop) {
    hamburgerTop.addEventListener("click", toggleSidebar);
  }

  // Fermer la sidebar si on clique sur l'overlay (en mobile)
  if (overlay) {
    overlay.addEventListener("click", function() {
      sidebar.classList.remove("collapsed");
      overlay.classList.remove("active");
      if (mainContent) {
        mainContent.style.marginLeft = "220px";
      }
    });
  }
  
  // Gérer le responsive
  function handleResize() {
    if (window.innerWidth <= 900) {
      // Mode mobile
      if (!sidebar.classList.contains("collapsed")) {
        sidebar.style.left = "-220px";
      } else {
        sidebar.style.left = "0";
      }
    } else {
      // Mode desktop
      sidebar.style.left = "0";
    }
  }
  
  window.addEventListener("resize", handleResize);
  handleResize(); // Appel initial
});
