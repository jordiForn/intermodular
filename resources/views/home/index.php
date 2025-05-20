<?php
use App\Models\Producte;
use App\Models\Servei;
use App\Core\DB;
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
                            <img src="../public/images/<?= htmlspecialchars($producte->imatge) ?>" alt="<?= htmlspecialchars($producte->nom) ?>">
                            <h3><?= htmlspecialchars($producte->nom) ?></h3>
                            <p><?= htmlspecialchars(substr($producte->descripcio, 0, 100)) . (strlen($producte->descripcio) > 100 ? '...' : '') ?></p>
                            <p class="price"><?= number_format($producte->preu, 2, ",", ".") ?>€</p>
                            <p>Estoc disponible: <?= number_format($producte->estoc, 0, ",", ".") ?></p>
                            <div class="tooltip-container">
                                <button onclick="event.preventDefault(); addToCart('<?= addslashes($producte->nom) ?>', <?= $producte->preu ?>, <?= $producte->id ?>, <?= $producte->estoc ?>)">Afegir al Carret</button>
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
    
    <div class="service-category" id="service-category">
        <button class="toggle-button">🏡 Serveis per a jardins</button>
        <div class="service-garden">
            <?php
            $serveisJardins = Servei::where('cat', 'jardins')->get();
            if (count($serveisJardins) > 0) {
                foreach ($serveisJardins as $servei) {
                    $nom = htmlspecialchars($servei->nom);
                    $preu_base = number_format($servei->preu_base, 2, ",", ".");
                    echo "<div class='service-card'>";
                    echo "<h3>$nom</h3>";
                    echo "<p class='price'>$preu_base €/h</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hi ha serveis disponibles per a jardins.</p>";
            }
            ?>
        </div>
    </div>
    <div class="service-category">
        <button class="toggle-button">🏊 Serveis per a piscines</button>
        <div class="service-pool">
            <?php
            $serveisPiscines = Servei::where('cat', 'piscines')->get();
            if (count($serveisPiscines) > 0) {
                foreach ($serveisPiscines as $servei) {
                    $nom = htmlspecialchars($servei->nom);
                    $preu_base = number_format($servei->preu_base, 2, ",", ".");
                    echo "<div class='service-card'>";
                    echo "<h3>$nom</h3>";
                    echo "<p class='price'>$preu_base €/h</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hi ha serveis disponibles per a piscines.</p>";
            }
            ?>
        </div>
    </div>
</div>

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
        
        // Initialize tooltips
        initializeTooltips();
    });
    
    function initializeTooltips() {
        const tooltipContainers = document.querySelectorAll('.tooltip-container');
        tooltipContainers.forEach(container => {
            const button = container.querySelector('button');
            const tooltip = container.querySelector('.tooltip-text');
            
            button.addEventListener('mouseover', function() {
                // Get cart data from localStorage
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                
                // Update tooltip text
                tooltip.textContent = `${totalItems} ítems - ${totalPrice.toFixed(2)}€`;
            });
        });
    }
    
    function addToCart(name, price, id, stock) {
        event.preventDefault();
        
        // Get existing cart or initialize empty array
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        // Check if product already in cart
        const existingItem = cart.find(item => item.id === id);
        
        if (existingItem) {
            // Don't exceed available stock
            if (existingItem.quantity < stock) {
                existingItem.quantity += 1;
            } else {
                alert('No hi ha més estoc disponible d\'aquest producte');
                return;
            }
        } else {
            // Add new item
            cart.push({
                id: id,
                name: name,
                price: price,
                quantity: 1,
                stock: stock
            });
        }
        
        // Save updated cart
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Update cart count in header if it exists
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCountElement.textContent = totalItems;
        }
        
        // Show success message
        alert(`${name} afegit al carret!`);
    }
</script>
