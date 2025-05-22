<?php 
use App\Core\Auth; 
use App\Models\Producte;
use App\Core\QueryBuilder;

// Get pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 9; // Show 9 products per page (3x3 grid)
$offset = ($page - 1) * $perPage;

// Get total count for pagination
$totalProducts = Producte::where('estoc', '>', 0)->count();
$totalPages = ceil($totalProducts / $perPage);

// Get products with ordering
$qb = new QueryBuilder(Producte::class);
$qb->where('estoc', '>', 0);
$qb->orderByRaw("CASE 
    WHEN categoria = 'Plantes i llavors' THEN 1
    WHEN categoria = 'Terra i adobs' THEN 2
    WHEN categoria = 'Ferramentes' THEN 3
    ELSE 4
END, nom", '');
$qb->limit($perPage)->offset($offset);
$productes = $qb->get();

// Group products by category
$categories = [];
foreach ($productes as $producte) {
    $categories[$producte->categoria][] = $producte;
}
?>

<div class="container mt-5 pt-4">
    <h2>Llista de Productes</h2>
    <a href="#service-category">Busques serveis?</a>
    
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success mt-3">
            <?= session()->get('success') ?>
        </div>
    <?php endif; ?>
    
    <?php foreach ($categories as $categoria => $productes): ?>
        <div class="product-category">
            <button class="toggle-button"><?= htmlspecialchars($categoria) ?></button>
            <div class="product-list">
                <?php foreach ($productes as $producte): ?>
                    <a href="public/productes/show.php?id=<?= htmlspecialchars($producte->id) ?>" class="product-link">
                        <div class="product-card">
                            <img src="../../public/images/<?= htmlspecialchars($producte->imatge) ?>" alt="<?= htmlspecialchars($producte->nom) ?>">
                            <h3><?= htmlspecialchars($producte->nom) ?></h3>
                            <p><?= htmlspecialchars(substr($producte->descripcio, 0, 100)) . (strlen($producte->descripcio) > 100 ? '...' : '') ?></p>
                            <p class="price"><?= number_format($producte->preu, 2, ",", ".") ?>€</p>
                            <p>Estoc disponible: <?= number_format($producte->estoc, 0, ",", ".") ?></p>
                            <div class="tooltip-container">
                                <button 
                                    data-name="<?= addslashes($producte->nom) ?>"
                                    data-price="<?= $producte->preu ?>"
                                    data-id="<?= $producte->id ?>"
                                    data-stock="<?= $producte->estoc ?>"
                                >Afegir al Carret</button>
                                <span class="tooltip-text">0 ítems - 0,00€</span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination-container text-center my-4">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="btn btn-outline-success">&laquo; Anterior</a>
            <?php endif; ?>

            <span class="mx-3">Pàgina <?= $page ?> de <?= $totalPages ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="btn btn-outline-success">Següent &raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-button');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productList = this.nextElementSibling;
                productList.classList.toggle('active');
            });
            
            // Activate the first category by default
            if (button === toggleButtons[0]) {
                button.nextElementSibling.classList.add('active');
            }
        });
        
        // Initialize cart functionality
        if (typeof initializeCart === 'function') {
            initializeCart();
        }
    });
</script>
