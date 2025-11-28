/**
 * ============================================
 * GESTION DE LA MODIFICATION D'UN SHOOTING
 * ============================================
 * 
 * Ce script gère la modification d'un shooting
 * depuis l'interface d'administration.
 * 
 * @file Modifier_shooting.js
 * @package Orüme\Admin\JS
 * @version 1.0.0
 */

document.addEventListener("DOMContentLoaded", function () {
    const editModal = document.getElementById("editModal");
    const closeEdit = document.querySelector(".close-edit");
    const editForm = document.getElementById("editForm");

    if (!editModal || !editForm) {
        console.error("❌ Le modal ou le formulaire de modification n'existe pas dans le DOM.");
        return;
    }

    editModal.style.display = "none";

    let currentCard = null;
    let currentId = null;

    // Quand on clique sur "Modifier" - Utilisation de la délégation d'événements
    document.addEventListener("click", function(e) {
        if (e.target.closest(".btn-edit")) {
            const btn = e.target.closest(".btn-edit");
            const card = btn.closest(".portfolio-card");
            if (!card) return;

            currentCard = card;
            currentId = card.getAttribute('data-id');

            // Récupérer les infos existantes
            const name = card.querySelector("h3").textContent.replace("Client : ", "").trim();
            const date = card.querySelector("p:nth-child(2)").textContent.replace("Date : ", "").trim();

            // Remplir le formulaire
            document.getElementById("editClientName").value = name;
            document.getElementById("editDate").value = formatMonthInput(date);

            // Affiche le modal
            editModal.style.display = "flex";
        }
    });

    // Fermer le modal
    if (closeEdit) {
        closeEdit.addEventListener("click", () => {
            editModal.style.display = "none";
        });
    }

    window.addEventListener("click", (e) => {
        if (e.target === editModal) {
            editModal.style.display = "none";
        }
    });

    // Soumission du formulaire
    editForm.addEventListener("submit", function (e) {
        e.preventDefault();
        if (!currentCard || !currentId) return;

        const newName = document.getElementById("editClientName").value.trim();
        const newDate = document.getElementById("editDate").value;
        const newImage = document.getElementById("editImage").files[0];

        // Créer FormData pour l'envoi
        const formData = new FormData();
        formData.append('id', currentId);
        formData.append('clientName', newName);
        formData.append('dateRealisation', newDate);
        if (newImage) {
            formData.append('image', newImage);
        }

        // Envoyer à l'API
        fetch('api/update_shooting.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour les infos dans la carte
                currentCard.querySelector("h3").innerHTML = `Client : ${newName}`;
                currentCard.querySelector("p:nth-child(2)").innerHTML = `<i class="fa-solid fa-calendar"></i> Date : ${formatDateDisplay(newDate)}`;

                // Si une nouvelle image est choisie, on la remplace
                if (newImage) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        currentCard.querySelector(".portfolio-img").src = e.target.result;
                    };
                    reader.readAsDataURL(newImage);
                } else if (data.image_path) {
                    currentCard.querySelector(".portfolio-img").src = data.image_path;
                }

                alert('✅ Modifications enregistrées avec succès');
                editModal.style.display = "none";
            } else {
                alert('❌ Erreur : ' + data.message);
            }
        })
        .catch(err => {
            console.error('Erreur :', err);
            alert('Une erreur est survenue lors de la modification.');
        });
    });

    // Fonctions utilitaires
    function formatMonthInput(text) {
        const mois = {
            "January": "01", "February": "02", "March": "03", "April": "04",
            "May": "05", "June": "06", "July": "07", "August": "08",
            "September": "09", "October": "10", "November": "11", "December": "12",
            "Janvier": "01", "Février": "02", "Mars": "03", "Avril": "04",
            "Mai": "05", "Juin": "06", "Juillet": "07", "Août": "08",
            "Septembre": "09", "Octobre": "10", "Novembre": "11", "Décembre": "12"
        };
        const [m, y] = text.split(" ");
        return y && mois[m] ? `${y}-${mois[m]}` : "";
    }

    function formatDateDisplay(monthVal) {
        const [y, m] = monthVal.split("-");
        const noms = [
            "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
            "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
        ];
        return `${noms[parseInt(m) - 1]} ${y}`;
    }
});

