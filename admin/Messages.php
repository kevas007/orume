<?php
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="fr">
  <?php
     include 'adminpartials/head.php';
  ?>
<body>

<?php
    include 'adminpartials/aside.php';
?>
  <!-- Main -->
  <main class="main-content">
    <header class="topbar">
      <h1>Messages</h1>
    </header>

    <!-- Cartes récap -->
    <section class="cards-summary">
      <?php
      // Charger les statistiques depuis la base de données
      require_once __DIR__ . '/../partials/connect.php';
      
      $totalMessages = 0;
      $nonLus = 0;
      
      if (isset($connect) && $connect) {
          // Compter tous les messages
          $queryTotal = "SELECT COUNT(*) as total FROM messages";
          $resultTotal = mysqli_query($connect, $queryTotal);
          if ($resultTotal) {
              $row = mysqli_fetch_assoc($resultTotal);
              $totalMessages = $row['total'];
          }
          
          // Compter les messages non lus
          $queryNonLus = "SELECT COUNT(*) as total FROM messages WHERE statut = 'non_lu'";
          $resultNonLus = mysqli_query($connect, $queryNonLus);
          if ($resultNonLus) {
              $row = mysqli_fetch_assoc($resultNonLus);
              $nonLus = $row['total'];
          }
      }
      ?>
      <div class="card green">
        <i class="fas fa-envelope-open-text"></i>
        <div>
          <h3 id="totalMessagesCount"><?php echo $totalMessages; ?></h3>
          <p>Mails reçus</p>
        </div>
      </div>
      <div class="card orange">
        <i class="fas fa-envelope"></i>
        <div>
          <h3 id="nonLusCount"><?php echo $nonLus; ?></h3>
          <p>Mails non lus</p>
        </div>
      </div>
    </section>

    <!-- Liste des messages -->
    <section class="messages-list">
     <h2>Derniers messages</h2>
  <table>
    <thead>
      <tr>
        <th>Expéditeur</th>
        <th>Email</th>
        <th>Objet</th>
        <th>Message</th>
        <th>Date</th>
        <th>Statut</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Charger les messages depuis la base de données
      require_once __DIR__ . '/../partials/connect.php';
      
      $messages = [];
      if (isset($connect) && $connect) {
          $query = "SELECT * FROM messages ORDER BY created_at DESC";
          $result = mysqli_query($connect, $query);
          if ($result) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $messages[] = $row;
              }
          }
      }
      
      // Afficher les messages
      if (!empty($messages)) {
          foreach ($messages as $message) {
              $id = htmlspecialchars($message['id'], ENT_QUOTES, 'UTF-8');
              $nom = htmlspecialchars($message['nom'], ENT_QUOTES, 'UTF-8');
              $email = htmlspecialchars($message['email'], ENT_QUOTES, 'UTF-8');
              $sujet = htmlspecialchars($message['sujet'], ENT_QUOTES, 'UTF-8');
              $msg = htmlspecialchars(substr($message['message'], 0, 100), ENT_QUOTES, 'UTF-8');
              $date = date('d M Y', strtotime($message['created_at']));
              $statut = $message['statut'];
              
              $badgeClass = $statut === 'non_lu' ? 'unread' : ($statut === 'repondu' ? 'repondu' : 'read');
              $badgeText = $statut === 'non_lu' ? 'Non lu' : ($statut === 'repondu' ? 'Répondu' : 'Lu');
              ?>
              <tr data-id="<?php echo $id; ?>" 
                  data-statut="<?php echo $statut; ?>"
                  data-nom="<?php echo htmlspecialchars($nom, ENT_QUOTES, 'UTF-8'); ?>"
                  data-email="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                  data-sujet="<?php echo htmlspecialchars($sujet, ENT_QUOTES, 'UTF-8'); ?>"
                  data-message="<?php echo htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8'); ?>"
                  data-date="<?php echo $date; ?>"
                  class="message-row <?php echo $statut === 'non_lu' ? 'unread-row' : ''; ?>">
                <td class="sender-name">
                  <div class="sender-info">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo $nom; ?></span>
                  </div>
                </td>
                <td class="sender-email">
                  <i class="fas fa-envelope"></i>
                  <?php echo $email; ?>
                </td>
                <td class="message-subject">
                  <strong><?php echo $sujet; ?></strong>
                </td>
                <td class="message-content" style="cursor: pointer;" onclick="voirMessageFromRow(this)">
                  <div class="message-preview">
                    <?php echo $msg; ?><?php echo strlen($message['message']) > 100 ? '...' : ''; ?>
                  </div>
                </td>
                <td class="message-date">
                  <i class="far fa-calendar"></i>
                  <?php echo $date; ?>
                </td>
                <td>
                  <span class="badge <?php echo $badgeClass; ?>" id="badge-<?php echo $id; ?>">
                    <i class="fas fa-circle"></i> <?php echo $badgeText; ?>
                  </span>
                </td>
                <td class="actions">
                  <div class="action-buttons">
                    <button class="btn-view" onclick="voirMessageFromRow(this.closest('tr'))" title="Voir le message">
                      <i class="fa-solid fa-eye"></i>
                    </button>
                    <button class="btn-reply" onclick="repondre('<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>','<?php echo htmlspecialchars($sujet, ENT_QUOTES, 'UTF-8'); ?>')" title="Répondre">
                      <i class="fa-solid fa-reply"></i>
                    </button>
                    <button class="btn-delete" onclick="supprimerMessage(<?php echo $id; ?>)" title="Supprimer">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <?php
          }
      } else {
          ?>
          <tr>
            <td colspan="7" style="text-align: center; padding: 20px;">
              Aucun message pour le moment.
            </td>
          </tr>
          <?php
      }
      ?>
    </tbody>
  </table>
    </section>
  </main>

  <!-- Modal pour afficher le message complet -->
  <div id="messageModal" class="message-modal">
    <div class="message-modal-content">
      <div class="message-modal-header">
        <h2 id="modalSujet">Message</h2>
        <span class="message-modal-close" onclick="fermerModal()">&times;</span>
      </div>
      <div class="message-modal-body">
        <div class="message-info">
          <p><strong>Expéditeur :</strong> <span id="modalNom"></span></p>
          <p><strong>Email :</strong> <span id="modalEmail"></span></p>
          <p><strong>Date :</strong> <span id="modalDate"></span></p>
          <p><strong>Statut :</strong> <span id="modalStatut" class="badge"></span></p>
        </div>
        <div class="message-text">
          <h3>Message :</h3>
          <p id="modalMessage"></p>
        </div>
      </div>
      <div class="message-modal-footer">
        <button class="btn-mark-unread" id="btnMarkUnread" onclick="changerStatut('non_lu')" style="display: none;">
          <i class="fa-solid fa-envelope"></i> Marquer comme non lu
        </button>
        <button class="btn-mark-read" id="btnMarkRead" onclick="changerStatut('lu')" style="display: none;">
          <i class="fa-solid fa-envelope-open"></i> Marquer comme lu
        </button>
        <button class="btn-reply" onclick="repondreModal()">
          <i class="fa-solid fa-reply"></i> Répondre
        </button>
        <button class="btn-close-modal" onclick="fermerModal()">
          Fermer
        </button>
      </div>
    </div>
  </div>

  <!-- Scripts pour la page Messages -->
  <script src="js/sibebar.js"></script>
  <script src="js/active_sidebar.js"></script>
  <script src="js/repondre.js"></script>
  <script>
    let currentMessageId = null;
    let currentMessageEmail = '';
    let currentMessageSujet = '';

    // Fonction pour voir un message depuis une ligne du tableau
    function voirMessageFromRow(element) {
      const row = element.closest ? element.closest('tr') : element;
      if (!row) return;
      
      const id = row.getAttribute('data-id');
      const nom = row.getAttribute('data-nom');
      const email = row.getAttribute('data-email');
      const sujet = row.getAttribute('data-sujet');
      const message = row.getAttribute('data-message');
      const date = row.getAttribute('data-date');
      const statut = row.getAttribute('data-statut');
      
      voirMessage(id, nom, email, sujet, message, date, statut);
    }

    // Fonction pour voir un message
    function voirMessage(id, nom, email, sujet, message, date, statut) {
      currentMessageId = id;
      currentMessageEmail = email;
      currentMessageSujet = sujet;
      
      // Remplir le modal
      document.getElementById('modalNom').textContent = nom;
      document.getElementById('modalEmail').textContent = email;
      document.getElementById('modalSujet').textContent = sujet;
      document.getElementById('modalDate').textContent = date;
      document.getElementById('modalMessage').textContent = message;
      
      // Mettre à jour le badge de statut
      const badgeModal = document.getElementById('modalStatut');
      const badgeTable = document.getElementById(`badge-${id}`);
      
      if (statut === 'non_lu') {
        badgeModal.textContent = 'Non lu';
        badgeModal.className = 'badge unread';
        document.getElementById('btnMarkRead').style.display = 'inline-block';
        document.getElementById('btnMarkUnread').style.display = 'none';
      } else if (statut === 'repondu') {
        badgeModal.textContent = 'Répondu';
        badgeModal.className = 'badge repondu';
        document.getElementById('btnMarkRead').style.display = 'none';
        document.getElementById('btnMarkUnread').style.display = 'inline-block';
      } else {
        badgeModal.textContent = 'Lu';
        badgeModal.className = 'badge read';
        document.getElementById('btnMarkRead').style.display = 'none';
        document.getElementById('btnMarkUnread').style.display = 'inline-block';
      }
      
      // Afficher le modal
      document.getElementById('messageModal').style.display = 'block';
      
      // Si le message est non lu, le marquer automatiquement comme lu
      if (statut === 'non_lu') {
        changerStatut('lu', false);
      }
    }

    // Fonction pour fermer le modal
    function fermerModal() {
      document.getElementById('messageModal').style.display = 'none';
      currentMessageId = null;
    }

    // Fermer le modal en cliquant en dehors
    window.onclick = function(event) {
      const modal = document.getElementById('messageModal');
      if (event.target === modal) {
        fermerModal();
      }
    }

    // Fonction pour mettre à jour les statistiques
    function updateStatistics() {
      // Compter les messages non lus dans le tableau
      const nonLusRows = document.querySelectorAll('tr.message-row[data-statut="non_lu"]');
      const totalRows = document.querySelectorAll('tr.message-row[data-id]');
      
      const nonLusCount = nonLusRows.length;
      const totalCount = totalRows.length;
      
      // Mettre à jour les compteurs dans les cartes
      const nonLusElement = document.getElementById('nonLusCount');
      const totalElement = document.getElementById('totalMessagesCount');
      
      if (nonLusElement) {
        nonLusElement.textContent = nonLusCount;
      }
      
      if (totalElement) {
        totalElement.textContent = totalCount;
      }
    }

    // Fonction pour changer le statut d'un message
    function changerStatut(nouveauStatut, afficherMessage = true) {
      if (!currentMessageId) return;
      
      const formData = new FormData();
      formData.append('id', currentMessageId);
      formData.append('statut', nouveauStatut);
      
      fetch('api/update_message_status.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          // Mettre à jour le badge dans le tableau
          const row = document.querySelector(`tr[data-id="${currentMessageId}"]`);
          const badgeTable = document.getElementById(`badge-${currentMessageId}`);
          
          if (row && badgeTable) {
            row.setAttribute('data-statut', nouveauStatut);
            row.classList.remove('unread-row');
            
            if (nouveauStatut === 'non_lu') {
              badgeTable.innerHTML = '<i class="fas fa-circle"></i> Non lu';
              badgeTable.className = 'badge unread';
              row.classList.add('unread-row');
            } else if (nouveauStatut === 'repondu') {
              badgeTable.innerHTML = '<i class="fas fa-circle"></i> Répondu';
              badgeTable.className = 'badge repondu';
            } else {
              badgeTable.innerHTML = '<i class="fas fa-circle"></i> Lu';
              badgeTable.className = 'badge read';
            }
          }
          
          // Mettre à jour le badge dans le modal
          const badgeModal = document.getElementById('modalStatut');
          if (badgeModal) {
            if (nouveauStatut === 'non_lu') {
              badgeModal.innerHTML = '<i class="fas fa-circle"></i> Non lu';
              badgeModal.className = 'badge unread';
              document.getElementById('btnMarkRead').style.display = 'inline-block';
              document.getElementById('btnMarkUnread').style.display = 'none';
            } else if (nouveauStatut === 'repondu') {
              badgeModal.innerHTML = '<i class="fas fa-circle"></i> Répondu';
              badgeModal.className = 'badge repondu';
              document.getElementById('btnMarkRead').style.display = 'none';
              document.getElementById('btnMarkUnread').style.display = 'inline-block';
            } else {
              badgeModal.innerHTML = '<i class="fas fa-circle"></i> Lu';
              badgeModal.className = 'badge read';
              document.getElementById('btnMarkRead').style.display = 'none';
              document.getElementById('btnMarkUnread').style.display = 'inline-block';
            }
          }
          
          // Mettre à jour les statistiques
          updateStatistics();
          
          if (afficherMessage) {
            alert('Statut mis à jour avec succès');
          }
        } else {
          alert('Erreur : ' + data.message);
        }
      })
      .catch(err => {
        console.error('Erreur :', err);
        alert('Une erreur est survenue lors de la mise à jour du statut.');
      });
    }

    // Fonction pour répondre depuis le modal
    function repondreModal() {
      if (currentMessageEmail && currentMessageSujet) {
        repondre(currentMessageEmail, currentMessageSujet);
      }
    }

    // Fonction pour supprimer un message
    function supprimerMessage(id) {
      if (confirm("Voulez-vous vraiment supprimer ce message ?")) {
        fetch(`api/delete_message.php?id=${id}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            // Supprimer la ligne du tableau
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
              row.style.transition = 'opacity 0.3s';
              row.style.opacity = '0';
              setTimeout(() => {
                row.remove();
                // Mettre à jour les statistiques après suppression
                updateStatistics();
              }, 300);
            }
            alert('Message supprimé avec succès');
          } else {
            alert('Erreur : ' + data.message);
          }
        })
        .catch(err => {
          console.error('Erreur :', err);
          alert('Une erreur est survenue lors de la suppression.');
        });
      }
    }
    
    // Mettre à jour les statistiques au chargement de la page
    updateStatistics();
  </script>

</body>
</html>
