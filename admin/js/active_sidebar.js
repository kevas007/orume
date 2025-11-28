/**
 * ============================================
 * GESTION DES CLASSES ACTIVES DANS LA SIDEBAR
 * ============================================
 * 
 * Ce script gère automatiquement les classes actives
 * des liens de la sidebar selon la page courante
 * 
 * @file active_sidebar.js
 * @package Orüme\Admin\JS
 * @version 1.0.0
 */

document.addEventListener("DOMContentLoaded", function() {
    // Récupérer le nom de la page actuelle
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    
    // Mapper les pages aux classes actives
    const pageMap = {
        'index.php': 'activeAcceuil',
        'Messages.php': 'activeMesssage',
        'portfolio.php': 'active',
        'affiche.php': 'activeAffiche',
        'identites.php': 'activeIdentité',
        'shooting.php': 'activeShooting'
    };
    
    // Trouver tous les liens du menu
    const menuLinks = document.querySelectorAll('.menu a');
    
    // Retirer toutes les classes actives
    menuLinks.forEach(link => {
        link.classList.remove('active', 'activeAcceuil', 'activeMesssage', 'activeAffiche', 'activeIdentité', 'activeShooting');
    });
    
    // Ajouter la classe active correspondante
    const activeClass = pageMap[currentPage];
    if (activeClass) {
        menuLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && href.includes(currentPage.replace('.php', ''))) {
                link.classList.add(activeClass);
            }
        });
    }
});

