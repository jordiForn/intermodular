<?php
// Admin Products Management View
?>

<div class="admin-products">
    <h1 class="mb-4 text-highlight">Gestió de Productes</h1>
    
    <div class="card mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-highlight">Llistat de Productes</h5>
            <a href="<?= BASE_URL ?>/productes/create.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Nou Producte
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imatge</th>
                            <th>Nom</th>
                            <th>Categoria</th>
                            <th>Preu</th>
                            <th>Stock</th>
                            <th>Accions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product->id ?></td>
                                <td>
                                    <img src="<?= BASE_URL ?>/images/<?= $product->imatge ?>" 
                                         alt="<?= htmlspecialchars($product->nom) ?>" 
                                         class="img-thumbnail" style="max-width: 50px;">
                                </td>
                                <td><?= htmlspecialchars($product->nom) ?></td>
                                <td><?= htmlspecialchars($product->categoria) ?></td>
                                <td><?= number_format($product->preu, 2) ?> €</td>
                                <td>
                                    <input type="number" class="form-control form-control-sm stock-input" 
                                           data-product-id="<?= $product->id ?>" 
                                           value="<?= $product->stock ?>" min="0" style="width: 70px;">
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>/productes/edit.php?id=<?= $product->id ?>" 
                                           class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/productes/show.php?id=<?= $product->id ?>" 
                                           class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" 
                                           title="Veure">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-product" 
                                                data-product-id="<?= $product->id ?>" data-bs-toggle="tooltip" 
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteProductModalLabel">Confirmar Eliminació</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Estàs segur que vols eliminar aquest producte? Aquesta acció no es pot desfer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle stock input changes
    document.querySelectorAll('.stock-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.productId;
            const newStock = this.value;
            updateProductStock(productId, newStock);
        });
    });
    
    // Handle delete button clicks
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const deleteLink = document.getElementById('confirmDelete');
            deleteLink.href = `${BASE_URL}/productes/destroy.php?id=${productId}`;
            
            // Show the modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteProductModal'));
            deleteModal.show();
        });
    });
});
</script>
