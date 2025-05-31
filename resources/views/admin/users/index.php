<?php
use App\Core\Auth;
$request = request();
$session = $request->session();
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-highlight">Gestió d'Usuaris</h1>
        <a href="<?= BASE_URL ?>/admin/users/create.php" class="btn btn-success">
            <i class="fas fa-plus-circle me-2"></i>Nou Usuari
        </a>
    </div>

    <!-- Alerts for messages -->
    <?php if ($session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $session->getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Users table -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 text-highlight">Llista d'Usuaris</h5>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <input type="text" id="userSearch" class="form-control" placeholder="Cercar usuaris...">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="usersTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nom d'usuari</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Data de creació</th>
                            <th>Última actualització</th>
                            <th class="text-center">Accions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user->id ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-<?= $user->role === 'admin' ? 'danger' : 'success' ?> rounded-circle me-2">
                                            <span class="avatar-text"><?= strtoupper(substr($user->username, 0, 1)) ?></span>
                                        </div>
                                        <?= htmlspecialchars($user->username) ?>
                                        <?php if ($user->id === Auth::user()->id): ?>
                                            <span class="badge bg-secondary ms-2">Tu</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($user->email) ?></td>
                                <td>
                                    <?php if ($user->role === 'admin'): ?>
                                        <span class="badge bg-danger">Administrador</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Usuari</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($user->created_at)) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($user->updated_at)) ?></td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="<?= BASE_URL ?>/admin/users/edit.php?id=<?= $user->id ?>" class="btn btn-sm btn-outline-primary me-2" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($user->id !== Auth::user()->id): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-user" 
                                                    data-bs-toggle="modal" data-bs-target="#deleteUserModal" 
                                                    data-user-id="<?= $user->id ?>" 
                                                    data-user-name="<?= htmlspecialchars($user->username) ?>" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger" disabled title="No pots eliminar el teu propi compte">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted">Total: <?= count($users) ?> usuaris</span>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="refreshUserList">
                        <i class="fas fa-sync-alt me-1"></i> Actualitzar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirmar Eliminació</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Estàs segur que vols eliminar l'usuari <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger">Aquesta acció no es pot desfer i eliminarà totes les dades associades a aquest usuari.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
                <form action="<?= BASE_URL ?>/admin/users/delete.php" method="post">
                    <input type="hidden" name="id" id="deleteUserId">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}
.avatar-text {
    font-size: 14px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete user modal
    const deleteUserModal = document.getElementById('deleteUserModal');
    if (deleteUserModal) {
        deleteUserModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            
            document.getElementById('deleteUserId').value = userId;
            document.getElementById('deleteUserName').textContent = userName;
        });
    }
    
    // Handle user search
    const userSearch = document.getElementById('userSearch');
    if (userSearch) {
        userSearch.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('usersTable');
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 1; i < rows.length; i++) {
                const username = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
                const email = rows[i].getElementsByTagName('td')[2].textContent.toLowerCase();
                
                if (username.includes(searchValue) || email.includes(searchValue)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    }
    
    // Handle refresh button
    const refreshButton = document.getElementById('refreshUserList');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            window.location.reload();
        });
    }
});
</script>
