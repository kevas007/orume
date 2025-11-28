/**
 * ============================================
 * GESTION DE LA SUPPRESSION D'UNE AFFICHE
 * ============================================
 * 
 * Ce script gère la suppression d'une affiche
 * depuis l'interface d'administration.
 * 
 * @file Supprimer_affiche.js
 * @package Orüme\Admin\JS
 * @version 1.0.0
 */

document.addEventListener("DOMContentLoaded", function() {
    const deleteButtons = document.querySelectorAll(".btn-delete");
    
    console.log("Supprimer_affiche.js - Nombre de boutons trouvés:", deleteButtons.length);

    deleteButtons.forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log("Bouton supprimer affiche cliqué, ID:", this.getAttribute('data-id'));
            if (confirm("Voulez-vous vraiment supprimer cette affiche ?")) {
                const card = this.closest(".portfolio-card");
                const id = this.getAttribute('data-id') || card?.getAttribute('data-id');
                
                if (!id) {
                    alert('Erreur : ID introuvable');
                    return;
                }
                
                fetch(`api/delete_affiche.php?id=${id}`, {
                    method: 'POST'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (card) {
                            card.style.transition = 'opacity 0.3s';
                            card.style.opacity = '0';
                            setTimeout(() => {
                                card.remove();
                                // Recharger la page pour mettre à jour les statistiques si nécessaire
                                window.location.reload();
                            }, 300);
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
    });
});

