/**
 * ============================================
 * GESTION DE LA SUPPRESSION D'UN PORTFOLIO
 * ============================================
 * 
 * Ce script gère la suppression d'un élément du portfolio
 * depuis l'interface d'administration. Il supprime visuellement
 * l'élément du DOM et devrait être connecté à une API backend
 * pour la suppression en base de données.
 * 
 * @file Supprimer_Info.js
 * @package Orüme\Admin\JS
 * @version 1.0.0
 */

document.addEventListener("DOMContentLoaded", function() {
    /**
     * Récupérer tous les boutons de suppression
     * @type {NodeList}
     */
    const deleteButtons = document.querySelectorAll(".btn-delete");
    
    // Debug: vérifier si les boutons sont trouvés
    console.log("Nombre de boutons supprimer trouvés:", deleteButtons.length);
    
    if (deleteButtons.length === 0) {
        console.warn("⚠️ Aucun bouton .btn-delete trouvé dans la page");
        // Réessayer après un court délai
        setTimeout(() => {
            const retryButtons = document.querySelectorAll(".btn-delete");
            console.log("Réessai - Nombre de boutons trouvés:", retryButtons.length);
            if (retryButtons.length > 0) {
                retryButtons.forEach(btn => attachDeleteListener(btn));
            }
        }, 500);
        return;
    }

    /**
     * Fonction pour attacher l'écouteur d'événement
     */
    function attachDeleteListener(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log("Bouton supprimer cliqué, ID:", this.getAttribute('data-id'));
            // Demander confirmation avant suppression
            if (confirm("Voulez-vous vraiment supprimer cet élément ?")) {
                // Récupérer la carte parente et l'ID
                const card = this.closest(".portfolio-card");
                const id = this.getAttribute('data-id') || card?.getAttribute('data-id');
                
                if (!id) {
                    alert('Erreur : ID introuvable');
                    return;
                }
                
                // Déterminer le type (site, affiche, etc.) depuis l'URL
                const currentPage = window.location.pathname;
                let apiEndpoint = 'delete_site.php';
                
                if (currentPage.includes('affiche')) {
                    apiEndpoint = 'delete_affiche.php';
                } else if (currentPage.includes('identit')) {
                    apiEndpoint = 'delete_identite.php';
                } else if (currentPage.includes('shooting')) {
                    apiEndpoint = 'delete_shooting.php';
                }
                
                // Appel API pour supprimer en base de données
                fetch(`api/${apiEndpoint}?id=${id}`, {
                    method: 'POST'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Supprimer visuellement la carte du DOM seulement si succès
                        if (card) {
                            card.style.transition = 'opacity 0.3s';
                            card.style.opacity = '0';
                            setTimeout(() => card.remove(), 300);
                        }
                    } else {
                        alert('Erreur : ' + data.message);
                    }
                })
                .catch(err => {
                    console.error('Erreur :', err);
                    alert('Une erreur est survenue lors de la suppression.');
                });
            }
        });
    }
    
    /**
     * Ajouter un écouteur d'événement à chaque bouton de suppression
     */
    deleteButtons.forEach(btn => {
        attachDeleteListener(btn);
    });
});
