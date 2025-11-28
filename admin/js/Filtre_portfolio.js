/**
 * ============================================
 * SYSTÈME DE FILTRAGE POUR LES PAGES PORTFOLIO
 * ============================================
 * 
 * Ce script gère le filtrage et le tri des éléments du portfolio
 * sur toutes les pages admin (Sites, Affiches, Identités, Shootings)
 * 
 * @file Filtre_portfolio.js
 * @package Orüme\Admin\JS
 * @version 1.0.0
 */

document.addEventListener("DOMContentLoaded", function() {
    const portfolioGrid = document.getElementById("portfolioGrid");
    const filterClient = document.getElementById("filterClient");
    const filterDate = document.getElementById("filterDate");
    const sortOrder = document.getElementById("sortOrder");
    const resetFilters = document.getElementById("resetFilters");

    if (!portfolioGrid) {
        console.warn("⚠️ Portfolio grid introuvable, le système de filtrage ne peut pas fonctionner.");
        return;
    }

    // Stocker toutes les cartes originales (clonées pour préserver l'état initial)
    let allCards = [];
    let originalCardsHTML = [];
    
    // Fonction pour initialiser les cartes
    function initializeCards() {
        const cards = portfolioGrid.querySelectorAll(".portfolio-card");
        allCards = Array.from(cards);
        // Stocker le HTML de chaque carte pour pouvoir les restaurer
        originalCardsHTML = allCards.map(card => card.outerHTML);
    }
    
    // Initialiser au chargement
    initializeCards();
    
    /**
     * Extraire les données d'une carte pour le filtrage
     */
    function getCardData(card) {
        const nameElement = card.querySelector("h3");
        const dateElement = card.querySelector("p:nth-child(2)");
        
        const name = nameElement ? nameElement.textContent.replace("Client : ", "").trim() : "";
        const dateText = dateElement ? dateElement.textContent.replace("Date : ", "").trim() : "";
        
        // Convertir la date en format YYYY-MM pour la comparaison
        let dateValue = "";
        if (dateText) {
            const mois = {
                "Janvier": "01", "Février": "02", "Mars": "03", "Avril": "04",
                "Mai": "05", "Juin": "06", "Juillet": "07", "Août": "08",
                "Septembre": "09", "Octobre": "10", "Novembre": "11", "Décembre": "12",
                "January": "01", "February": "02", "March": "03", "April": "04",
                "May": "05", "June": "06", "July": "07", "August": "08",
                "September": "09", "October": "10", "November": "11", "December": "12"
            };
            const parts = dateText.split(" ");
            if (parts.length === 2) {
                const moisStr = parts[0];
                const annee = parts[1];
                if (mois[moisStr] && annee) {
                    dateValue = `${annee}-${mois[moisStr]}`;
                }
            }
        }
        
        return { name, dateValue, card };
    }

    /**
     * Filtrer les cartes
     */
    function filterCards() {
        const clientFilter = filterClient ? filterClient.value.toLowerCase().trim() : "";
        const dateFilter = filterDate ? filterDate.value : "";
        
        let filteredCards = allCards.map(getCardData);
        
        // Filtrer par nom de client
        if (clientFilter) {
            filteredCards = filteredCards.filter(data => 
                data.name.toLowerCase().includes(clientFilter)
            );
        }
        
        // Filtrer par date
        if (dateFilter) {
            filteredCards = filteredCards.filter(data => 
                data.dateValue.startsWith(dateFilter)
            );
        }
        
        // Trier les cartes
        const sortValue = sortOrder ? sortOrder.value : "desc";
        filteredCards.sort((a, b) => {
            switch(sortValue) {
                case "asc":
                    return a.dateValue.localeCompare(b.dateValue);
                case "desc":
                    return b.dateValue.localeCompare(a.dateValue);
                case "name-asc":
                    return a.name.localeCompare(b.name);
                case "name-desc":
                    return b.name.localeCompare(a.name);
                default:
                    return 0;
            }
        });
        
        // Afficher les résultats
        portfolioGrid.innerHTML = "";
        if (filteredCards.length === 0) {
            portfolioGrid.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">
                    <p style="font-size: 18px;">Aucun résultat trouvé.</p>
                    <p style="font-size: 14px; margin-top: 10px;">Essayez de modifier vos critères de recherche.</p>
                </div>
            `;
        } else {
            filteredCards.forEach(data => {
                // Cloner la carte pour éviter les problèmes de référence DOM
                const clonedCard = data.card.cloneNode(true);
                portfolioGrid.appendChild(clonedCard);
            });
        }
        
        // Réattacher les event listeners pour les boutons modifier et supprimer
        reattachButtonListeners();
    }

    /**
     * Réinitialiser les filtres
     */
    function resetAllFilters(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // Réinitialiser les valeurs des filtres
        if (filterClient) filterClient.value = "";
        if (filterDate) filterDate.value = "";
        if (sortOrder) sortOrder.value = "desc";
        
        // Réafficher toutes les cartes originales
        portfolioGrid.innerHTML = "";
        
        // Recréer les cartes à partir du HTML stocké
        originalCardsHTML.forEach(cardHTML => {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = cardHTML;
            const card = tempDiv.firstElementChild;
            if (card) {
                portfolioGrid.appendChild(card);
            }
        });
        
        // Réinitialiser allCards avec les nouvelles références
        initializeCards();
        
        // Réattacher les event listeners
        if (typeof attachEventListeners === 'function') {
            attachEventListeners();
        }
        
        // Réinitialiser les listeners des boutons modifier et supprimer
        reattachButtonListeners();
    }
    
    /**
     * Réattacher les listeners des boutons après réinitialisation
     */
    function reattachButtonListeners() {
        // Réattacher les listeners pour les boutons modifier
        const editButtons = portfolioGrid.querySelectorAll('.btn-edit');
        editButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const id = this.getAttribute('data-id');
                if (id && typeof window.openEditModal === 'function') {
                    window.openEditModal(id);
                }
            });
        });
        
        // Réattacher les listeners pour les boutons supprimer
        const deleteButtons = portfolioGrid.querySelectorAll('.btn-delete');
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const id = this.getAttribute('data-id');
                if (id) {
                    // Appeler la fonction de suppression appropriée
                    if (typeof window.supprimerElement === 'function') {
                        window.supprimerElement(id);
                    } else if (typeof supprimerElement === 'function') {
                        supprimerElement(id);
                    }
                }
            });
        });
    }

    // Attacher les event listeners
    if (filterClient) {
        filterClient.addEventListener("input", filterCards);
    }
    
    if (filterDate) {
        filterDate.addEventListener("change", filterCards);
    }
    
    if (sortOrder) {
        sortOrder.addEventListener("change", filterCards);
    }
    
    if (resetFilters) {
        resetFilters.addEventListener("click", resetAllFilters);
    }
    
    // Réinitialiser allCards si de nouvelles cartes sont ajoutées
    // (après un rechargement de page ou ajout dynamique)
    const observer = new MutationObserver(function(mutations) {
        // Ne mettre à jour que si ce n'est pas un filtrage en cours
        const currentCards = portfolioGrid.querySelectorAll(".portfolio-card");
        if (currentCards.length !== allCards.length) {
            initializeCards();
        }
    });
    
    if (portfolioGrid) {
        observer.observe(portfolioGrid, { childList: true, subtree: true });
    }
    
    // Exposer la fonction de réinitialisation globalement pour pouvoir l'appeler depuis d'autres scripts
    window.resetPortfolioFilters = resetAllFilters;
});

