/**
 * ============================================
 * SYSTÈME DE FILTRAGE DU PORTFOLIO
 * ============================================
 * 
 * Ce script gère le filtrage des éléments du portfolio
 * selon leur catégorie (Sites, Shooting, Identité visuelle, Affiches).
 * Il permet d'afficher/masquer les éléments avec des animations fluides.
 * 
 * @file filtrePortfolio.js
 * @package Orüme\Assets\JS
 * @version 1.0.0
 */

document.addEventListener("DOMContentLoaded", () => {
    console.log("[filtrePortfolio] Script chargé");
    
    /**
     * Récupérer le paramètre de filtre depuis l'URL
     * @returns {string} Le filtre actif ("all", "sites", "shooting", "identite", "affiches")
     */
    function getFilterFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        const filter = urlParams.get('filter') || 'all';
        const validFilters = ['all', 'sites', 'shooting', 'identite', 'affiches'];
        return validFilters.includes(filter) ? filter : 'all';
    }
    
    /**
     * Mettre à jour l'URL avec le nouveau filtre (sans rechargement)
     * @param {string} filter - Le filtre à appliquer
     */
    function updateURL(filter) {
        const newURL = filter === 'all' 
            ? window.location.pathname 
            : `${window.location.pathname}?filter=${filter}`;
        
        // Utiliser l'History API pour mettre à jour l'URL sans rechargement
        window.history.pushState({ filter: filter }, '', newURL);
    }
    
    /**
     * Récupérer tous les boutons de filtre
     * @type {Array<HTMLElement>}
     */
    const filterButtons = Array.from(document.querySelectorAll(".filter-buttons button"));
    
    /**
     * Récupérer tous les éléments du portfolio
     * @type {Array<HTMLElement>}
     */
    const items = Array.from(document.querySelectorAll(".portfolio-grid .item"));

    // Vérifier que les éléments existent (debug)
    if (!filterButtons.length) {
        console.error("[filtrePortfolio] Aucun bouton de filtre trouvé (.filter-buttons button).");
        return;
    } else {
        console.log(`[filtrePortfolio] ${filterButtons.length} boutons de filtre trouvés:`, 
            filterButtons.map(b => b.getAttribute("data-filter")));
    }

    if (!items.length) {
        console.error("[filtrePortfolio] Aucune .item trouvée dans .portfolio-grid.");
        return;
    } else {
        console.log(`[filtrePortfolio] ${items.length} éléments trouvés dans .portfolio-grid.`);
        // Afficher les classes de chaque élément pour debug
        items.forEach((item, i) => {
            const classes = Array.from(item.classList);
            console.log(`[filtrePortfolio] Item ${i}: classes =`, classes.join(", "));
        });
    }

    /**
     * Afficher les éléments d'une catégorie spécifique
     * 
     * @param {string} category - Catégorie à afficher ("all", "sites", "shooting", etc.)
     * @param {boolean} updateURLParam - Si true, met à jour l'URL (défaut: true)
     */
    function showCategory(category, updateURLParam = true) {
        console.log(`[filtrePortfolio] Affichage de la catégorie: ${category}`);
        
        // Mettre à jour l'URL si demandé
        if (updateURLParam) {
            updateURL(category);
        }
        
        // Mettre à jour l'état actif des boutons
        filterButtons.forEach(btn => {
            const btnFilter = btn.getAttribute("data-filter");
            if (btnFilter === category) {
                btn.classList.add("active");
            } else {
                btn.classList.remove("active");
            }
        });

        // Filtrer les éléments
        let visibleCount = 0;
        let hiddenCount = 0;
        
        items.forEach((item, index) => {
            // Récupérer toutes les classes de l'élément
            const itemClasses = item.className.split(' ').filter(c => c.trim() !== '');
            
            // Vérifier si l'élément appartient à la catégorie
            // Les catégories possibles: sites, shooting, identite, affiches
            let hasCategory = false;
            
            if (category === "all") {
                hasCategory = true;
            } else {
                // Vérifier si l'élément a la classe correspondant à la catégorie
                hasCategory = itemClasses.some(cls => cls === category);
            }
            
            // Debug pour les premiers éléments
            if (index < 3) {
                console.log(`[filtrePortfolio] Item ${index}: classes=[${itemClasses.join(", ")}], category="${category}", hasCategory=${hasCategory}`);
            }
            
            if (hasCategory) {
                // Afficher l'élément
                item.classList.remove("hide");
                // Forcer l'affichage avec les styles inline si nécessaire
                item.style.display = "";
                item.style.visibility = "";
                item.style.opacity = "";
                visibleCount++;
            } else {
                // Masquer l'élément
                item.classList.add("hide");
                hiddenCount++;
            }
        });
        
        console.log(`[filtrePortfolio] Résultat: ${visibleCount} visibles, ${hiddenCount} cachés sur ${items.length} éléments`);
        
        // Forcer un reflow pour s'assurer que les changements sont appliqués
        void items[0]?.offsetHeight;
    }

    /**
     * Attacher les écouteurs d'événements aux boutons de filtre
     */
    filterButtons.forEach((btn, index) => {
        const filterValue = btn.getAttribute("data-filter");
        console.log(`[filtrePortfolio] Bouton ${index}: data-filter="${filterValue}"`);
        
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            const filter = (filterValue || "").trim();
            
            if (!filter) {
                console.error("[filtrePortfolio] data-filter manquant sur le bouton", btn);
                return;
            }
            
            console.log(`[filtrePortfolio] Clic sur le bouton: ${filter}`);
            showCategory(filter, true);
        });
    });

    // Gérer les changements d'URL (bouton retour/avant du navigateur)
    window.addEventListener('popstate', (e) => {
        const filter = e.state && e.state.filter ? e.state.filter : getFilterFromURL();
        console.log(`[filtrePortfolio] Changement d'URL détecté, filtre: ${filter}`);
        showCategory(filter, false); // Ne pas mettre à jour l'URL car elle vient de changer
    });

    // Appliquer le filtre depuis l'URL au chargement initial
    const initialFilter = getFilterFromURL();
    console.log(`[filtrePortfolio] Filtre initial depuis l'URL: ${initialFilter}`);
    showCategory(initialFilter, false); // Ne pas mettre à jour l'URL car c'est le chargement initial
});
