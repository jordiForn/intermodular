<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>Editar usuaris</title>
    <script>
        function editUser(nom, cognom, email, telefon, username, rol) {
            document.getElementById('edit-nom').value = nom;
            document.getElementById('edit-cognom').value = cognom;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-telefon').value = telefon;
            document.getElementById('edit-username').value = username;
            document.getElementById('edit-rol').value = rol;
            document.getElementById('edit-form').style.display = 'block';
        }

        function deleteUser(id) {
            if (confirm("Estàs segur que vols eliminar aquest usuari?")) {
                fetch('<?= BASE_URL ?>/clients/destroy.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + id
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                });
            }
        }

        function save() {
            let nom = document.getElementById('edit-nom').value;
            let cognom = document.getElementById('edit-cognom').value;
            let email = document.getElementById('edit-email').value;
            let telefon = document.getElementById('edit-telefon').value;
            let username = document.getElementById('edit-username').value;
            let rol = document.getElementById('edit-rol').value;

            fetch('<?= BASE_URL ?>/clients/update.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `nom=${encodeURIComponent(nom)}&cognom=${encodeURIComponent(cognom)}&email=${encodeURIComponent(email)}&telefon=${encodeURIComponent(telefon)}&nom_login=${encodeURIComponent(username)}&rol=${encodeURIComponent(rol)}`
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            });
        }
    </script>
</head>
<body>
    <header>
        <h1>Canviar ususaris</h1>
        <div><a href="<?= BASE_URL ?>/index.php">Pàgina principal</a></div>
    </header>

    <div class="container">
        <h2>Llista d'usuaris</h2>

        <?php if (!empty($clientsData)): ?>
            <table border="1">
                <tr>
                    <th>Nom</th>
                    <th>Cognom</th>
                    <th>Email</th>
                    <th>Telefon</th>
                    <th>Nom d'usuari</th>
                    <th>Rol</th>
                    <th>Acció</th>
                </tr>
                <?php foreach ($clientsData as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['cognom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['tlf']) ?></td>
                        <td><?= htmlspecialchars($user['nom_login']) ?></td>
                        <td><?= htmlspecialchars($user['rol']) ?></td>
                        <td>
                            <a href="javascript:void(0);" onclick="editUser(
                                '<?= htmlspecialchars($user['nom']) ?>', 
                                '<?= htmlspecialchars($user['cognom']) ?>', 
                                '<?= htmlspecialchars($user['email']) ?>', 
                                '<?= htmlspecialchars($user['tlf']) ?>',
                                '<?= htmlspecialchars($user['nom_login']) ?>', 
                                '<?= htmlspecialchars($user['rol']) ?>'
                            )" class="edit-icon">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            <a href="javascript:void(0);" onclick="deleteUser(
                                '<?= htmlspecialchars($user['id']) ?>'
                            )" class="delete-icon">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No hi ha usuaris disponibles.</p>
        <?php endif; ?>
    </div>

    <div id="edit-form" style="display: none;">
        <h2>Editar usuaris</h2>
        <form action="javascript:void(0);" onsubmit="save()">
            <label for="edit-nom">Nom:</label>
            <input type="text" id="edit-nom" name="nom" required>
            <label for="edit-cognom">Cognom:</label>
            <input type="text" id="edit-cognom" name="cognom">
            <label for="edit-email">Email:</label>
            <input type="email" id="edit-email" name="email" required>
            <label for="edit-telefon">Telèfon:</label>
            <input type="text" id="edit-telefon" name="telefon" required>
            <label for="edit-username">Nom d'usuari:</label>
            <input type="text" id="edit-username" name="nom_login" required readonly>
            <label for="edit-rol">Rol:</label>
            <input type="text" id="edit-rol" name="rol" required>
            <button type="submit">Guardar canvis</button>
        </form>
    </div>
</body>
</html>
