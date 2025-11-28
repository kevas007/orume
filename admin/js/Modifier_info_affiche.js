// --------------- MODIFIER UNE AFFICHE ---------------
document.addEventListener("DOMContentLoaded", function () {
  const editModal = document.getElementById("editModalAffiche");
  const closeEdit = document.querySelector(".close-edit");
  const editForm = document.getElementById("editAfficheForm");

  if (!editModal || !editForm) {
    console.error("❌ Le modal ou le formulaire de modification n'existe pas dans le DOM.");
    return;
  }

  // Cache toujours le modal au départ
  editModal.style.display = "none";

  // Variable pour stocker la carte à modifier
  let currentCard = null;

  // Quand on clique sur "Modifier" - Utilisation de la délégation d'événements
  document.addEventListener("click", function(e) {
    if (e.target.closest(".btn-edit")) {
      const btn = e.target.closest(".btn-edit");
      const card = btn.closest(".portfolio-card");
      if (!card) return;

      currentCard = card;

      // Récupérer les infos existantes
      const name = card.querySelector("h3").textContent.replace("Client : ", "").trim();
      const date = card.querySelector("p:nth-child(2)").textContent.replace("Date : ", "").trim();

      // Remplir le formulaire
      const editClientInput = document.getElementById("editClientAffiche");
      const editDateInput = document.getElementById("editDateAffiche");
      const editEmailInput = document.getElementById("editEmailAffiche");
      
      if (editClientInput) editClientInput.value = name;
      if (editDateInput) editDateInput.value = formatMonthInput(date);
      // Les affiches n'ont pas d'email, donc on ne remplit pas ce champ si le champ existe
      if (editEmailInput) editEmailInput.value = '';

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
    if (!currentCard) return;

    const cardId = currentCard.getAttribute('data-id');
    const editClientInput = document.getElementById("editClientAffiche");
    const editDateInput = document.getElementById("editDateAffiche");
    const editImageInput = document.getElementById("editImageAffiche");
    
    if (!editClientInput || !editDateInput) {
      alert('Erreur : Champs du formulaire introuvables');
      return;
    }
    
    const newName = editClientInput.value.trim();
    const newDate = editDateInput.value;
    const newImage = editImageInput ? editImageInput.files[0] : null;

    // Créer FormData pour l'envoi
    const formData = new FormData();
    formData.append('id', cardId);
    formData.append('clientName', newName);
    formData.append('dateRealisation', newDate);
    if (newImage) {
      formData.append('image', newImage);
    }

    // Envoyer à l'API
    fetch('api/update_affiche.php', {
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

  // ----------- Fonctions utilitaires -----------
  function formatMonthInput(text) {
    const mois = {
      "January": "01", "February": "02", "March": "03", "April": "04",
      "May": "05", "June": "06", "July": "07", "August": "08",
      "September": "09", "October": "10", "November": "11", "December": "12",
      "Janvier": "01", "Février": "02", "Mars": "03", "Avril": "04", "Mai": "05", "Juin": "06",
      "Juillet": "07", "Août": "08", "Septembre": "09", "Octobre": "10", "Novembre": "11", "Décembre": "12"
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
