/**
 * ============================================
 * GESTION DE L'AJOUT D'UN SHOOTING
 * ============================================
 * 
 * Ce script gère l'ajout dynamique d'un shooting dans le portfolio
 * depuis l'interface d'administration.
 * 
 * @file Ajout_shooting.js
 * @package Orüme\Admin\JS
 * @version 1.0.0
 */

document.addEventListener("DOMContentLoaded", function() {
    /**
     * Récupérer le bouton d'ajout
     * @type {HTMLElement|null}
     */
    const addBtn = document.getElementById("addShootingBtn");
    
    // Vérifier que le bouton existe
    if (!addBtn) {
        console.error("❌ Bouton 'addShootingBtn' introuvable dans le DOM.");
        return;
    }

    /**
     * Gérer la soumission du formulaire
     */
    const form = document.getElementById("shootingForm");
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault(); // Empêcher le rechargement de la page
            
            // Récupérer les valeurs du formulaire
            const clientName = document.getElementById("clientName").value.trim();
            const date = document.getElementById("dateRealisation").value;
            const imageInput = document.getElementById("image");

            // Validation des champs requis
            if (!clientName || !date || !imageInput.files.length) {
                alert("⚠️ Veuillez remplir tous les champs avant d'ajouter un shooting.");
                return;
            }

            // Envoyer les données au backend via API
            const formData = new FormData();
            formData.append('clientName', clientName);
            formData.append('dateRealisation', date);
            formData.append('image', imageInput.files[0]);

            fetch('api/add_shooting.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Afficher un message de succès
                    alert('✅ ' + data.message);
                    
                    // Recharger la page pour afficher le nouvel élément
                    window.location.reload();
                } else {
                    alert('❌ Erreur : ' + data.message);
                }
            })
            .catch(err => {
                console.error('Erreur :', err);
                alert('Une erreur est survenue lors de l\'ajout. Veuillez réessayer.');
            });
        });
    }
});

