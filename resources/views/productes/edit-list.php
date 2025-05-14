<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>Editar un producte</title>
    <script>
        function editProduct(id, nom, descripcio, preu, estoc) {
            document.getElementById('edit-form').style.display = 'block';
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nom').value = nom;
            document.getElementById('edit-descripcio').value = descripcio;
            document.getElementById('edit-preu').value = preu;
            document.getElementById('edit-estoc').value = estoc;

            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                const cardId = card.getAttribute('data-id');
                if (cardId === id) {
                    card.style.display = 'block';
                    card.setAttribute('id', 'editing-product');
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('edit-nom').addEventListener('input', function() {
                document.querySelector('#editing-product h3').innerText = this.value;
            });
            document.getElementById('edit-descripcio').addEventListener('input', function() {
                document.querySelector('#editing-product p.description').innerText = this.value;
            });
            document.getElementById('edit-preu').addEventListener('input', function() {
                document.querySelector('#editing-product p.price').innerText = parseFloat(this.value).toFixed(2) + '€';
            });
            document.getElementById('edit-estoc').addEventListener('input', function() {
                document.querySelector('#editing-product p.stock').innerText = 'Estoc disponible: ' + this.value;
            });
        }

        function deleteProduct(id) {
            if (confirm("Estàs segur que vols eliminar aquest producte?")) {
                fetch('<?= BASE_URL ?>/productes/destroy.php', {
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
    </script>
</head>
<body>
    <header>
        <h1>Canviar producte</h1>
        <div><a href="<?= BASE_URL ?>/index.php">Pàgina principal</a></div>
    </header>

    <div class="container">
        <div class="form-container">
            <form action="<?= BASE_URL ?>/productes/edit-list.php" method="POST">
                <label for="category">Selecciona una categoria:</label>
                <select name="category" id="category">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= $cat === $category ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Mostrar productes</button>
            </form>
        </div>
        
        <?php if (!empty($products)): ?>
            <div class="product-list" style="display: flex; justify-content: space-around;">
                <?php foreach ($products as $producte): ?>
                    <div class="product-card" data-id="<?= $producte->id ?>">
                        <img src="<?= BASE_URL ?>/images/<?= htmlspecialchars($producte->imatge) ?>" alt="<?= htmlspecialchars($producte->nom) ?>">
                        <h3><?= htmlspecialchars($producte->nom) ?></h3>
                        <p class="description"><?= htmlspecialchars($producte->descripcio) ?></p>
                        <p class="price"><?= number_format($producte->preu, 2, ",", ".") ?>€</p>
                        <p class="stock">Estoc disponible: <?= number_format($producte->estoc, 0, ",", ".") ?></p>

                        <a href="javascript:void(0);" onclick="editProduct('<?= $producte->id ?>', '<?= htmlspecialchars($producte->nom) ?>', '<?= htmlspecialchars($producte->descripcio) ?>', '<?= $producte->preu ?>', '<?= $producte->estoc ?>')" class="edit-icon">
                            <i class="fas fa-pencil-alt"></i>
                        </a>

                        <a href="javascript:void(0);" onclick="deleteProduct('<?= $producte->id ?>')" class="delete-icon">
                            <i class="fas fa-trash" style="color: red;"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="edit-form" style="display: none;">
        <h2>Editar producte</h2>
        <form action="<?= BASE_URL ?>/productes/update.php" method="POST">
            <input type="hidden" id="edit-id" name="id">
            <label for="edit-nom">Nom:</label>
            <input type="text" id="edit-nom" name="nom" required>
            <label for="edit-descripcio">Descripció:</label>
            <input type="text" id="edit-descripcio" name="descripcio" required>
            <label for="edit-preu">Preu:</label>
            <input type="number" id="edit-preu" name="preu" step="0.01" required>
            <label for="edit-estoc">Estoc:</label>
            <input type="number" id="edit-estoc" name="estoc" required>
            <button type="submit">Guardar canvis</button>
        </form>
    </div>
</body>
</html>
