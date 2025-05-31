<?php
// Admin Users Management View
?>

<div class="admin-users">
    <h1 class="mb-4 text-highlight">Gestió d'Usuaris</h1>
    
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 text-highlight">Llistat d'Usuaris</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom d'usuari</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Creat</th>
                            <th>Actualitzat</th>
                            <th>Accions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user->id ?></td>
                                <td><?= htmlspecialchars($user->username ?? '') ?></td>
                                <td><?= htmlspecialchars($user->email ?? '') ?></td>
                                <td><?= htmlspecialchars($user->role ?? '') ?></td>
                                <td><?= htmlspecialchars($user->created_at ?? '') ?></td>
                                <td><?= htmlspecialchars($user->updated_at ?? '') ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>/users/edit.php?id=<?= $user->id ?>" 
                                           class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-primary view-user" 
                                                data-user-id="<?= $user->id ?>" data-bs-toggle="tooltip" 
                                                title="Veure detalls">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-user" 
                                                data-user-id="<?= $user->id ?>" data-bs-toggle="tooltip" 
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>