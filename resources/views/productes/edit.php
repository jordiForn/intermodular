<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <title>Editar Producte</title>
</head>
<body>
    <header>
        <h1>Editar Producte</h1>
        <div><a href="<?= BASE_URL ?>/index.php">Pàgina principal</a></div>
    </header>

    <div class="container">
        <div class="form-container">
            <form action="<?= BASE_URL ?>/productes/update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $producte->id ?>">
                
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($producte->nom) ?>" required>
                
                <label for="descripcio">Descripció:</label>
                <textarea id="descripcio" name="descripcio" required><?= htmlspecialchars($producte->descripcio) ?></textarea>
                
                <label for="preu">Preu:</label>
                <input type="number" id="preu" name="preu" step="0.01" value="<?= $producte->preu ?>" required>
                
                <label for="estoc">Estoc:</label>
                <input type="number" id="estoc" name="estoc" value="<?= $producte->estoc ?>" required>
                
                <label for="categoria">Categoria:</label>
                <select id="categoria" name="categoria" required>
                    <?php foreach ($categories as $categoria): ?>
                        <option value="<?= htmlspecialchars($categoria) ?>" <?= $categoria === $producte->categoria ? 'selected' : '' ?>><?= htmlspecialchars($categoria) ?></option>
                    <?php endforeach; ?>
                </select>
                
                <label for="imatge">Imatge actual:</label>
                <img src="<?= BASE_URL ?>/images/<?= htmlspecialchars($producte->imatge) ?>" alt="<?= htmlspecialchars($producte->nom) ?>" style="max-width: 200px;">
                
                <label for="imatge">Nova imatge (opcional):</label>
                <input type="file" id="imatge" name="imatge">
                
                <button type="submit">Actualitzar Producte</button>
            </form>
        </div>
    </div>
</body>
</html>
