<?php
use App\Core\Auth;
$request = request();
$session = $request->session();
?>

<div class="container mt-4">

    <h1 class="mb-4">Llista de Comandes</h1>

    <!-- Mostrar mensajes de error y éxito si existen -->
    <?php include __DIR__ . '/../partials/messages.php'; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Data</th>
                <th>Total</th>
                <th>Estat</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comandes as $comanda): ?>
                <tr>
                    <td><?= htmlspecialchars($comanda->id) ?></td>
                    <td>
                        <?php $client = $comanda->client; ?>
                        <?php if ($client): ?>
                            <a href="<?= BASE_URL ?>/clients/show.php?id=<?= $client->id ?>">
                                <?= htmlspecialchars($client->nom) ?> <?= htmlspecialchars($client->cognom) ?>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Client desconegut</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($comanda->data_comanda) ?></td>
                    <td><?= number_format($comanda->total, 2) ?> €</td>
                    <td>
                        <span class="badge 
    <?php
        switch (mb_strtolower($comanda->estat)) {
            case 'pendent':
                echo 'bg-warning text-dark';
                break;
            case 'enviat':
                echo 'bg-info';
                break;
            case 'completat':
                echo 'bg-success';
                break;
            case 'cancel·lat':
                echo 'bg-danger';
                break;
            default:
                echo 'bg-secondary';
        }
    ?>">
    <?= htmlspecialchars($comanda->estat) ?>
</span>
                    </td>
                    <td>
                        <a href="show.php?id=<?= $comanda->id ?>" class="btn btn-info btn-sm">Veure</a>
                        <?php if(Auth::check() && Auth::role() === 'admin'): ?>
                            <a href="edit.php?id=<?= $comanda->id ?>" class="btn btn-warning btn-sm">Editar</a>
                            <form action="destroy.php" method="POST" class="d-inline" onsubmit="return confirm('Estàs segur que vols eliminar aquesta comanda?');">
                                <input type="hidden" name="id" value="<?= $comanda->id ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
