<?php
use App\Core\Auth;
$request = request();
$session = $request->session();
?>

<div class="container mt-4">

    <h1 class="mb-4">Llista de Clients</h1>

    <!-- Mostrar mensajes de error y éxito si existen -->
    <?php include __DIR__ . '/../partials/messages.php'; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Cognom</th>
                <th>Email</th>
                <th>Telèfon</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <?php $user = $client->user(); ?>
                <tr>
                    <td><?= htmlspecialchars($client->id) ?></td>
                    <td><?= htmlspecialchars($client->nom) ?></td>
                    <td><?= htmlspecialchars($client->cognom) ?></td>
                    <td><?= htmlspecialchars($user ? $user->email : '') ?></td>
                    <td><?= htmlspecialchars($client->tlf) ?></td>
                    <td>
                        <a href="show.php?id=<?= $client->id ?>" class="btn btn-info btn-sm">Veure</a>
                        <?php if(Auth::check() && Auth::role() === 'admin'): ?>
                            <a href="edit.php?id=<?= $client->id ?>" class="btn btn-warning btn-sm">Editar</a>
                            <form action="destroy.php" method="POST" class="d-inline" onsubmit="return confirm('Estàs segur que vols eliminar aquest client?');">
                                <input type="hidden" name="id" value="<?= $client->id ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
