<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <script>
        function toggleNewCategory(){
            const newCategoryDiv = document.getElementById("new-category");
            const newCategoryButton = document.getElementById("NewCategoryButton");
            if (newCategoryDiv.style.display === "none" || newCategoryDiv.style.display === "") {
                newCategoryDiv.style.display = "block";
                newCategoryButton.style.display = "none";
            } else {
                newCategoryDiv.style.display = "none";
                newCategoryButton.style.display = "block";
            }
        }
    </script>
    <title>Nou producte</title>
</head>
<body>
    <header>
        <h1>Afegir producte</h1>
        <div>
            <a href="<?= BASE_URL ?>/index.php">Pàgina principal</a>
        </div>
    </header>

    <div class="container">
        <h1>Nou producte</h1>
        <form class="form-container" action="<?= BASE_URL ?>/productes/store.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nom del producte</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Descripció</label>
                <input type="text" id="description" name="description">
            </div>
            <div class="form-group">
                <label for="price">Preu</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="stock">Estoc disponible</label>
                <input type="number" id="stock" name="stock" required>
            </div>
            <div class="form-group">
                <label for="category">Categoria</label>
                <select id="category" name="category">
                    <?php foreach ($categories as $categoria): ?>
                        <option value="<?= htmlspecialchars($categoria) ?>"><?= htmlspecialchars($categoria) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Imatge</label>
                <input type="file" id="image" name="image">
            </div>
            <div class="form-group">
                <button type="button" id="NewCategoryButton" style="display: block;" onclick="toggleNewCategory()">Nova categoria</button>
                <div id="new-category" style="display: none;">
                    <br>
                    <label for="new-category-name">Nom de la nova categoria</label>
                    <input type="text" id="new-category-name" name="new-category-name" placeholder="Nom de la nova categoria">
                    <button type="button" onclick="toggleNewCategory()">Cancel·lar</button>
                </div>
            </div>
            <button type="submit">Crear producte</button>
        </form>
    </div>
</body>
</html>
