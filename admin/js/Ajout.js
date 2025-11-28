/**
 * ============================================
 * GESTION DE L'AJOUT D'UN SITE PORTFOLIO
 * ============================================
 * 
 * Ce script gère l'ajout dynamique d'un site web dans le portfolio
 * depuis l'interface d'administration. Il permet d'ajouter une carte
 * visuellement dans le DOM et devrait être connecté à une API backend
 * pour la persistance en base de données.
 * 
 * @file Ajout.js
 * @package Orüme\Admin\JS
 * @version 1.0.0
 */

document.addEventListener("DOMContentLoaded", function() {
    // Déterminer le type de formulaire (sites, affiches, identites, shootings)
    const currentPage = window.location.pathname;
    console.log("🔍 Page actuelle:", currentPage);
    
    let formId, clientFieldId, dateFieldId, contactFieldId, imageFieldId, submitBtnId, apiEndpoint;
    
    if (currentPage.includes('affiche')) {
        formId = 'afficheForm';
        clientFieldId = 'clientAffiche';
        dateFieldId = 'dateAffiche';
        contactFieldId = null; // Les affiches n'ont pas de contact
        imageFieldId = 'imageAffiche';
        submitBtnId = 'addAfficheBtn';
        apiEndpoint = 'api/add_affiche.php';
        console.log("✅ Mode AFFICHE détecté");
    } else if (currentPage.includes('identite') || currentPage.includes('identites')) {
        formId = 'identiteForm';
        clientFieldId = 'clientIdentite';
        dateFieldId = 'dateIdentite';
        contactFieldId = null;
        imageFieldId = 'imageIdentite';
        submitBtnId = 'addIdentiteBtn';
        apiEndpoint = 'api/add_identite.php';
    } else if (currentPage.includes('shooting')) {
        formId = 'shootingForm';
        clientFieldId = 'clientName';
        dateFieldId = 'dateRealisation';
        contactFieldId = null;
        imageFieldId = 'image';
        submitBtnId = 'addShootingBtn';
        apiEndpoint = 'api/add_shooting.php';
    } else {
        // Par défaut : sites
        formId = 'portfolioForm';
        clientFieldId = 'clientName';
        dateFieldId = 'dateRealisation';
        contactFieldId = 'contact';
        imageFieldId = 'image';
        submitBtnId = 'addPortfolioBtn';
        apiEndpoint = 'api/add_site.php';
    }

    /**
     * Récupérer le formulaire et le bouton
     */
    const form = document.getElementById(formId);
    const submitBtn = document.getElementById(submitBtnId);
    
    // Vérifier que le formulaire existe
    if (!form) {
        console.log("ℹ️ Formulaire '" + formId + "' introuvable sur cette page.");
        return;
    }

    if (!submitBtn) {
        console.log("ℹ️ Bouton '" + submitBtnId + "' introuvable sur cette page.");
        return;
    }
    
        console.log("✅ Formulaire détecté:", formId, "API:", apiEndpoint);
    console.log("✅ Bouton détecté:", submitBtnId);
    
    // Vérifier que tous les champs existent
    const clientField = document.getElementById(clientFieldId);
    const dateField = document.getElementById(dateFieldId);
    const imageField = document.getElementById(imageFieldId);
    
    if (!clientField) console.error("❌ Champ client introuvable:", clientFieldId);
    if (!dateField) console.error("❌ Champ date introuvable:", dateFieldId);
    if (!imageField) console.error("❌ Champ image introuvable:", imageFieldId);

    /**
     * Gérer la soumission du formulaire
     */
    form.addEventListener("submit", (e) => {
        e.preventDefault(); // Empêcher le rechargement de la page
        console.log("🔄 Formulaire soumis !");
        
        // Récupérer les valeurs du formulaire
        const clientName = document.getElementById(clientFieldId).value.trim();
        const date = document.getElementById(dateFieldId).value;
        const imageInput = document.getElementById(imageFieldId);
        
        let contact = null;
        if (contactFieldId) {
            const contactField = document.getElementById(contactFieldId);
            contact = contactField ? contactField.value.trim() : null;
        }

        // Validation des champs requis
        if (!clientName || !date || !imageInput || !imageInput.files.length) {
            alert("⚠️ Veuillez remplir tous les champs requis avant d'ajouter.");
            return;
        }
        
        // Pour les sites, le contact est requis
        if (contactFieldId && !contact) {
            alert("⚠️ Veuillez remplir tous les champs requis avant d'ajouter.");
            return;
        }

        // Envoyer les données au backend via API
        const formData = new FormData();
        
        // Pour les affiches, utiliser les noms de champs spécifiques
        if (currentPage.includes('affiche')) {
            formData.append('clientAffiche', clientName);
            formData.append('dateAffiche', date);
            formData.append('imageAffiche', imageInput.files[0]);
        } else {
            // Pour les sites, identités et shootings, utiliser les mêmes noms
            formData.append('clientName', clientName);
            formData.append('dateRealisation', date);
            formData.append('image', imageInput.files[0]);
            
            // Ajouter le contact seulement pour les sites
            if (contactFieldId && contact) {
                formData.append('contact', contact);
            }
        }

        // Désactiver le bouton pendant l'envoi
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Enregistrement...';

        console.log("📤 Envoi des données à:", apiEndpoint);
        console.log("📋 Données:", {
            clientName: clientName,
            date: date,
            hasImage: imageInput.files.length > 0
        });

        fetch(apiEndpoint, {
            method: 'POST',
            body: formData
        })
        .then(res => {
            console.log("📥 Réponse reçue, status:", res.status);
            if (!res.ok) {
                throw new Error('HTTP error! status: ' + res.status);
            }
            return res.json();
        })
        .then(data => {
            console.log("📦 Données reçues:", data);
            if (data.success) {
                // Afficher un message de succès
                alert('✅ ' + data.message);
                
                // Recharger la page pour afficher le nouvel élément
                window.location.reload();
            } else {
                alert('❌ Erreur : ' + data.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Enregistrer';
            }
        })
        .catch(err => {
            console.error('❌ Erreur complète:', err);
            alert('Une erreur est survenue lors de l\'ajout. Veuillez réessayer. Consultez la console pour plus de détails.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Enregistrer';
        });
    });
});
