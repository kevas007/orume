<?php
require_once __DIR__ . '/auth.php';

// Vérifier que l'utilisateur est admin
if (!isAdmin()) {
    header('Location: index.php');
    exit;
}

include 'adminpartials/head.php';
?>
<!DOCTYPE html>
<html lang="fr">
<body>

<?php
    include 'adminpartials/aside.php';
?>
  <!-- Main -->
  <main class="main-content">
    <header class="topbar">
      <h1>Gestion des utilisateurs</h1>
    </header>

    <!-- Cartes récap -->
    <section class="cards-summary">
      <?php
      // Charger les statistiques depuis la base de données
      require_once __DIR__ . '/../partials/connect.php';
      
      $totalUsers = 0;
      $adminUsers = 0;
      
      if (isset($connect) && $connect) {
          // Compter tous les utilisateurs
          $queryTotal = "SELECT COUNT(*) as total FROM users";
          $resultTotal = mysqli_query($connect, $queryTotal);
          if ($resultTotal) {
              $row = mysqli_fetch_assoc($resultTotal);
              $totalUsers = $row['total'];
          }
          
          // Compter les admins
          $queryAdmin = "SELECT COUNT(*) as total FROM users WHERE role = 'admin'";
          $resultAdmin = mysqli_query($connect, $queryAdmin);
          if ($resultAdmin) {
              $row = mysqli_fetch_assoc($resultAdmin);
              $adminUsers = $row['total'];
          }
      }
      ?>
      <div class="card green">
        <i class="fas fa-users"></i>
        <div>
          <h3><?php echo $totalUsers; ?></h3>
          <p>Utilisateurs total</p>
        </div>
      </div>
      <div class="card orange">
        <i class="fas fa-user-shield"></i>
        <div>
          <h3><?php echo $adminUsers; ?></h3>
          <p>Administrateurs</p>
        </div>
      </div>
    </section>

    <!-- Bouton d'ajout -->
    <section class="add-section">
      <button class="btn-add" id="addUserBtn">
        <i class="fas fa-plus"></i>
        Ajouter un utilisateur
      </button>
    </section>

    <!-- Liste des utilisateurs -->
    <section class="users-list">
      <h2>Liste des utilisateurs</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Date de création</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Charger les utilisateurs depuis la base de données
          require_once __DIR__ . '/../partials/connect.php';
          
          $users = [];
          if (isset($connect) && $connect) {
              $query = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC";
              $result = mysqli_query($connect, $query);
              if ($result) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      $users[] = $row;
                  }
              }
          }
          
          // Afficher les utilisateurs
          if (!empty($users)) {
              foreach ($users as $user) {
                  $id = htmlspecialchars($user['id'] ?? '', ENT_QUOTES, 'UTF-8');
                  $username = htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8');
                  $email = htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8');
                  $role = htmlspecialchars($user['role'] ?? 'user', ENT_QUOTES, 'UTF-8');
                  $createdAt = $user['created_at'] ?? '';
                  
                  // Formater la date
                  $date = 'N/A';
                  if (!empty($createdAt)) {
                      $timestamp = strtotime($createdAt);
                      if ($timestamp !== false) {
                          $date = date('d/m/Y H:i', $timestamp);
                      }
                  }
                  
                  // Badge de rôle
                  $roleClass = $role === 'admin' ? 'badge-admin' : 'badge-user';
                  $roleText = $role === 'admin' ? 'Admin' : 'Utilisateur';
                  
                  echo "<tr data-id='{$id}' data-username='{$username}' data-email='{$email}' data-role='{$role}'>";
                  echo "<td>{$id}</td>";
                  echo "<td>{$username}</td>";
                  echo "<td>{$email}</td>";
                  echo "<td><span class='badge {$roleClass}'>{$roleText}</span></td>";
                  echo "<td>{$date}</td>";
                  echo "<td class='actions'>";
                  echo "<button class='btn-edit' onclick='editUser({$id})' title='Modifier'>";
                  echo "<i class='fas fa-edit'></i>";
                  echo "</button>";
                  // Ne pas permettre la suppression de son propre compte
                  if ($id != getUserId()) {
                      echo "<button class='btn-delete' onclick='deleteUser({$id})' title='Supprimer'>";
                      echo "<i class='fas fa-trash'></i>";
                      echo "</button>";
                  }
                  echo "</td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='6' style='text-align: center; padding: 20px;'>Aucun utilisateur trouvé</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </section>
  </main>

  <!-- Modal d'ajout/modification -->
  <div id="userModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modalTitle">Ajouter un utilisateur</h2>
        <span class="close" onclick="closeModal()">&times;</span>
      </div>
      <form id="userForm" onsubmit="saveUser(event)">
        <input type="hidden" id="userId" name="userId">
        <div class="form-group">
          <label for="username">Nom d'utilisateur *</label>
          <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
          <label for="email">Email *</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password" id="passwordLabel">Mot de passe *</label>
          <input type="password" id="password" name="password">
          <small id="passwordHelp">Laissez vide pour ne pas modifier le mot de passe</small>
        </div>
        <div class="form-group">
          <label for="role">Rôle *</label>
          <select id="role" name="role" required>
            <option value="user">Utilisateur</option>
            <option value="admin">Administrateur</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeModal()">Annuler</button>
          <button type="submit" class="btn-save">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>

  <script src="js/sibebar.js"></script>
  <script src="js/active_sidebar.js"></script>
  <script>
    // Ouvrir le modal d'ajout
    document.getElementById('addUserBtn').addEventListener('click', function() {
        document.getElementById('modalTitle').textContent = 'Ajouter un utilisateur';
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
        document.getElementById('password').required = true;
        document.getElementById('passwordLabel').innerHTML = 'Mot de passe *';
        document.getElementById('passwordHelp').style.display = 'none';
        document.getElementById('userModal').style.display = 'block';
    });

    // Fermer le modal
    function closeModal() {
        document.getElementById('userModal').style.display = 'none';
    }

    // Fermer le modal en cliquant en dehors
    window.onclick = function(event) {
        const modal = document.getElementById('userModal');
        if (event.target == modal) {
            closeModal();
        }
    }

    // Éditer un utilisateur
    function editUser(id) {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (!row) return;

        document.getElementById('modalTitle').textContent = 'Modifier l\'utilisateur';
        document.getElementById('userId').value = id;
        document.getElementById('username').value = row.dataset.username;
        document.getElementById('email').value = row.dataset.email;
        document.getElementById('role').value = row.dataset.role;
        document.getElementById('password').required = false;
        document.getElementById('passwordLabel').innerHTML = 'Nouveau mot de passe';
        document.getElementById('passwordHelp').style.display = 'block';
        document.getElementById('userModal').style.display = 'block';
    }

    // Sauvegarder un utilisateur
    async function saveUser(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const userId = formData.get('userId');
        const isEdit = userId !== '';
        
        const data = {
            username: formData.get('username'),
            email: formData.get('email'),
            password: formData.get('password'),
            role: formData.get('role')
        };
        
        // Si c'est une modification et que le mot de passe est vide, ne pas l'envoyer
        if (isEdit && !data.password) {
            delete data.password;
        }
        
        try {
            const url = isEdit ? `api/update_user.php?id=${userId}` : 'api/add_user.php';
            const method = isEdit ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(result.message || 'Utilisateur enregistré avec succès');
                location.reload();
            } else {
                alert(result.message || 'Erreur lors de l\'enregistrement');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'enregistrement');
        }
    }

    // Supprimer un utilisateur
    async function deleteUser(id) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
            return;
        }
        
        try {
            const response = await fetch(`api/delete_user.php?id=${id}`, {
                method: 'DELETE'
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(result.message || 'Utilisateur supprimé avec succès');
                location.reload();
            } else {
                alert(result.message || 'Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        }
    }
  </script>
</body>
</html>

