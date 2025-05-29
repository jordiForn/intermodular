<!-- Products Management Content - No HTML structure, content only -->
<div class="container-fluid py-4">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-seedling me-2" style="color: #4daa57;"></i>Gestió de Productes
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/" style="color: #754668;">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Productes</li>
                </ol>
            </nav>
        </div>
        <a href="<?= BASE_URL ?>/productes/create.php" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> Nou Producte
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Products Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #4daa57;">
                                Total Productes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalProducts ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-seedling fa-2x" style="color: #4daa57; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Estoc Baix
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $lowStockCount ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Out of Stock Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Sense Estoc
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $outOfStockCount ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-danger" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Value Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #4daa57;">
                                Valor d'Inventari
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($totalValue, 2) ?> €</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x" style="color: #4daa57; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
            <h6 class="m-0 font-weight-bold" style="color: #754668;">
                <i class="fas fa-list me-2"></i>Llistat de Productes
            </h6>
            <div>
                <div class="input-group">
                    <input type="text" id="productSearch" class="form-control" placeholder="Cercar productes..." style="max-width: 200px;">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-1"></i>Categoria
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" data-category="all">Totes les categories</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php foreach ($categories as $category): ?>
                            <li><a class="dropdown-item" href="#" data-category="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="productsTable">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="px-3 py-3">ID</th>
                            <th class="px-3 py-3">Imatge</th>
                            <th class="px-3 py-3">Nom</th>
                            <th class="px-3 py-3">Categoria</th>
                            <th class="px-3 py-3">Preu</th>
                            <th class="px-3 py-3">Stock</th>
                            <th class="px-3 py-3">Accions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr data-category="<?= htmlspecialchars($product->categoria) ?>" class="border-bottom">
                                <td class="px-3 py-3"><?= $product->id ?></td>
                                <td class="px-3 py-3">
                                    <img src="<?= BASE_URL ?>/images/<?= $product->imatge ?>" 
                                         alt="<?= htmlspecialchars($product->nom) ?>" 
                                         class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                </td>
                                <td class="px-3 py-3">
                                    <div class="fw-bold"><?= htmlspecialchars($product->nom) ?></div>
                                </td>
                                <td class="px-3 py-3">
                                    <span class="badge" style="background-color: #754668; color: white;">
                                        <?= htmlspecialchars($product->categoria) ?>
                                    </span>
                                </td>
                                <td class="px-3 py-3">
                                    <span class="fw-bold"><?= number_format($product->preu, 2) ?> €</span>
                                </td>
                                <td class="px-3 py-3">
                                    <?php if ($product->stock <= 0): ?>
                                        <span class="badge bg-danger">Sense estoc</span>
                                    <?php elseif ($product->stock <= 5): ?>
                                        <span class="badge bg-warning text-dark">Baix: <?= $product->stock ?></span>
                                    <?php else: ?>
                                        <span class="badge" style="background-color: #4daa57; color: white;"><?= $product->stock ?></span>
                                    <?php endif; ?>
                                    
                                    <button class="btn btn-sm btn-outline-secondary ms-2 edit-stock-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editStockModal" 
                                            data-product-id="<?= $product->id ?>"
                                            data-product-name="<?= htmlspecialchars($product->nom) ?>"
                                            data-product-stock="<?= $product->stock ?>"
                                            title="Editar estoc">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>/productes/edit.php?id=<?= $product->id ?>" 
                                           class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/productes/show.php?id=<?= $product->id ?>" 
                                           class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" 
                                           title="Veure">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-product" 
                                                data-product-id="<?= $product->id ?>"
                                                data-product-name="<?= htmlspecialchars($product->nom) ?>"
                                                data-bs-toggle="tooltip" 
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

<!-- Edit Stock Modal -->
<div class="modal fade" id="editStockModal" tabindex="-1" aria-labelledby="editStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #4daa57; color: white;">
                <h5 class="modal-title" id="editStockModalLabel">
                    <i class="fas fa-edit me-2"></i>Actualitzar Estoc
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStockForm">
                    <input type="hidden" id="productId" name="productId">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Producte</label>
                        <input type="text" class="form-control" id="productName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="currentStock" class="form-label">Estoc Actual</label>
                        <input type="number" class="form-control" id="currentStock" name="currentStock" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="newStock" class="form-label">Nou Estoc</label>
                        <input type="number" class="form-control" id="newStock" name="newStock" min="0" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
                <button type="button" class="btn btn-success" id="saveStockBtn">
                    <i class="fas fa-save me-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteProductModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminació
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                </div>
                <p class="text-center">Estàs segur que vols eliminar el producte <strong id="deleteProductName"></strong>?</p>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Atenció:</strong> Aquesta acció no es pot desfer.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i>Eliminar
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Edit stock modal
    const editStockModal = document.getElementById('editStockModal');
    if (editStockModal) {
        editStockModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const productStock = button.getAttribute('data-product-stock');
            
            const modalProductId = document.getElementById('productId');
            const modalProductName = document.getElementById('productName');
            const modalCurrentStock = document.getElementById('currentStock');
            const modalNewStock = document.getElementById('newStock');
            
            modalProductId.value = productId;
            modalProductName.value = productName;
            modalCurrentStock.value = productStock;
            modalNewStock.value = productStock;
        });
    }
    
    // Save stock button
    const saveStockBtn = document.getElementById('saveStockBtn');
    if (saveStockBtn) {
        saveStockBtn.addEventListener('click', function() {
            const productId = document.getElementById('productId').value;
            const newStock = document.getElementById('newStock').value;
            
            // Here you would normally make an AJAX request to update the stock
            // For demonstration, we'll just show an alert
            alert(`Estoc actualitzat per al producte ID ${productId}: ${newStock} unitats`);
            
            // Close the modal
            const modal = bootstrap.Modal.getInstance(editStockModal);
            modal.hide();
            
            // Reload the page to show updated stock
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
    }
    
    // Delete product modal
    const deleteButtons = document.querySelectorAll('.delete-product');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            
            document.getElementById('deleteProductName').textContent = productName;
            document.getElementById('confirmDeleteBtn').href = `${BASE_URL}/productes/destroy.php?id=${productId}`;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteProductModal'));
            deleteModal.show();
        });
    });
    
    // Product search functionality
    const productSearch = document.getElementById('productSearch');
    if (productSearch) {
        productSearch.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#productsTable tbody tr');
            
            rows.forEach(row => {
                const productName = row.cells[2].textContent.toLowerCase();
                const productCategory = row.getAttribute('data-category').toLowerCase();
                
                if (productName.includes(searchValue) || productCategory.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Category filter
    const categoryLinks = document.querySelectorAll('[data-category]');
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const category = this.getAttribute('data-category');
            const rows = document.querySelectorAll('#productsTable tbody tr');
            
            rows.forEach(row => {
                if (category === 'all' || row.getAttribute('data-category') === category) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});
</script>
