/* POUR LE SCROLLAGE DU MENU */
/* Robuste : attends que le DOM soit prêt */
document.addEventListener('DOMContentLoaded', () => {
  const navbar = document.querySelector('.navbar');
  if (!navbar) {
    console.warn('Navbar introuvable — selector .navbar');
    return;
  }

  let lastScroll = window.pageYOffset || document.documentElement.scrollTop;
  const threshold = 80; // ne cache qu'après ce nombre de px

  // Optionnel : accélération via requestAnimationFrame pour fluidité
  let ticking = false;

  window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        const current = window.pageYOffset || document.documentElement.scrollTop;

        if (current > lastScroll && current > threshold) {
          // on descend -> cacher
          navbar.classList.add('hide');
        } else {
          // on monte -> montrer
          navbar.classList.remove('hide');
        }

        lastScroll = current <= 0 ? 0 : current;
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
});




/* PAGE PORTFOLIO FILTRAGE*/
document.addEventListener('DOMContentLoaded', function() {
  const buttons = document.querySelectorAll('.filter-buttons button');
  const items = document.querySelectorAll('.portfolio-grid .item');
  const modal = document.getElementById('imageModal');
  const modalImg = document.querySelector('.modal-image');
  const closeBtn = document.querySelector('.modal .close');
  const nextBtn = document.querySelector('.modal .next');
  const prevBtn = document.querySelector('.modal .prev');

  // Vérifier que les éléments existent
  if (!modal || !modalImg || !closeBtn || !nextBtn || !prevBtn) {
    console.warn('Éléments de la modale introuvables sur la page portfolio');
  }

  let currentImages = [];
  let currentIndex = 0;

  // --- FILTRAGE ---
  if (buttons.length > 0 && items.length > 0) {
    buttons.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        buttons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filter = btn.getAttribute('data-filter');
        if (filter) {
          items.forEach(item => {
            if (filter === 'all' || item.classList.contains(filter)) {
              item.classList.remove('hide');
              item.style.display = '';
              item.style.visibility = '';
              item.style.opacity = '';
            } else {
              item.classList.add('hide');
            }
          });
        }
      });
    });
  }

  // --- OUVERTURE DE LA MODALE ---
  if (items.length > 0 && modal && modalImg) {
    items.forEach((item, index) => {
      item.addEventListener('click', () => {
        const activeBtn = document.querySelector('.filter-buttons .active');
        if (!activeBtn) return;
        
        const activeFilter = activeBtn.getAttribute('data-filter') || 'all';
        currentImages = Array.from(items)
          .filter(i => {
            if (activeFilter === 'all') return true;
            return i.classList.contains(activeFilter);
          })
          .map(i => {
            const img = i.querySelector('img');
            return img ? img.src : null;
          })
          .filter(src => src !== null);

        const clickedImg = item.querySelector('img');
        if (clickedImg) {
          currentIndex = currentImages.indexOf(clickedImg.src);
          if (currentIndex === -1) currentIndex = 0;
          openModal(currentImages[currentIndex]);
        }
      });
    });
  }

  function openModal(src) {
    if (modal && modalImg) {
      modal.style.display = 'block';
      modalImg.src = src;
    }
  }

  // --- FERMER MODALE ---
  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      if (modal) modal.style.display = 'none';
    });
  }
  
  if (modal) {
    modal.addEventListener('click', e => { 
      if (e.target === modal) modal.style.display = 'none'; 
    });
  }

  // --- NAVIGATION ---
  if (nextBtn && modalImg) {
    nextBtn.addEventListener('click', () => {
      if (currentImages.length > 0) {
        currentIndex = (currentIndex + 1) % currentImages.length;
        modalImg.src = currentImages[currentIndex];
      }
    });
  }

  if (prevBtn && modalImg) {
    prevBtn.addEventListener('click', () => {
      if (currentImages.length > 0) {
        currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
        modalImg.src = currentImages[currentIndex];
      }
    });
  }

  // --- Navigation clavier (facultatif) ---
  document.addEventListener('keydown', e => {
    if (modal && modal.style.display === 'block') {
      if (e.key === 'ArrowRight' && nextBtn) nextBtn.click();
      if (e.key === 'ArrowLeft' && prevBtn) prevBtn.click();
      if (e.key === 'Escape' && closeBtn) closeBtn.click();
    }
  });
});







// PAGE CONTACT----*//
// Ajoute une légère animation de focus sur les champs
const inputs = document.querySelectorAll('input, textarea');
inputs.forEach(input => {
  input.addEventListener('focus', () => {
    input.style.transition = '0.3s';
    input.style.transform = 'scale(1.02)';
  });
  input.addEventListener('blur', () => {
    input.style.transform = 'scale(1)';
  });
});






/* PAGE ACCEUIL PORTFOLIO ANIMATION */
document.addEventListener('DOMContentLoaded', function() {
  // Ne s'exécuter que sur la page d'accueil (acceuil.php)
  if (!document.querySelector('.hero') || !document.querySelector('.portfolio')) {
    return; // Pas la page d'accueil, sortir silencieusement
  }
  
  const images = document.querySelectorAll('.portfolio .card img');
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightbox-img');
  const closeBtn = document.querySelector('.lightbox .close');
  const prevBtn = document.querySelector('.lightbox .prev');
  const nextBtn = document.querySelector('.lightbox .next');
  
  // Vérifier que tous les éléments existent
  if (!lightbox || !lightboxImg || !closeBtn || !prevBtn || !nextBtn || images.length === 0) {
    // Ne pas afficher de warning si on n'est pas sur la page d'accueil
    return;
  }
  
  let currentIndex = 0;

  function showImage(index) {
    if (index < 0 || index >= images.length) return;
    currentIndex = index;
    lightboxImg.src = images[index].src;
    lightbox.style.display = 'flex';
  }

  images.forEach((img, index) => {
    img.addEventListener('click', () => showImage(index));
  });

  closeBtn.addEventListener('click', () => lightbox.style.display = 'none');
  nextBtn.addEventListener('click', () => {
    if (images.length > 0) {
      showImage((currentIndex + 1) % images.length);
    }
  });
  prevBtn.addEventListener('click', () => {
    if (images.length > 0) {
      showImage((currentIndex - 1 + images.length) % images.length);
    }
  });

  // Fermer avec la touche Échap
  document.addEventListener('keydown', (e) => {
    if (lightbox.style.display === 'flex') {
      if (e.key === "Escape") lightbox.style.display = 'none';
      if (e.key === "ArrowRight" && images.length > 0) {
        showImage((currentIndex + 1) % images.length);
      }
      if (e.key === "ArrowLeft" && images.length > 0) {
        showImage((currentIndex - 1 + images.length) % images.length);
      }
    }
  });
});


