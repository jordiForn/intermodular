<?php 
use App\Core\Auth; 
use App\Http\Middlewares\Security\CsrfMiddleware;
?>

<div class="container mt-4">
    <h1 class="mb-4">Edició en Lot de Productes</h1>

    <!-- Alert container for dynamic messages -->
    <div id="alert-container">
        <!-- Mostrar mensajes de error y éxito si existen -->
        <?php include __DIR__ . '/../partials/messages.php'; ?>
    </div>

    <!-- Filter form -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Filtrar Productes</h5>
        </div>
        <div class="card-body">
            <form id="search-form" action="edit-list.php" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select class="form-select" id="categoria" name="categoria">
                        <option value="">Totes les categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= $categoria === $cat ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Cerca per nom</label>
                    <input type="text" class="form-control" id="search" name="search" value="<?= htmlspecialchars($searchTerm ?? '') ?>" placeholder="Cerca productes...">
                </div>
                <div class="col-md-4">
                    <label for="sort_by" class="form-label">Ordenar per</label>
                    <div class="input-group">
                        <select class="form-select" id="sort_by" name="sort_by">
                            <option value="nom" <?= ($sortBy ?? 'nom') === 'nom' ? 'selected' : '' ?>>Nom</option>
                            <option value="preu" <?= ($sortBy ?? '') === 'preu' ? 'selected' : '' ?>>Preu</option>
                            <option value="estoc" <?= ($sortBy ?? '') === 'estoc' ? 'selected' : '' ?>>Estoc</option>
                            <option value="categoria" <?= ($sortBy ?? '') === 'categoria' ? 'selected' : '' ?>>Categoria</option>
                        </select>
                        <select class="form-select" id="sort_order" name="sort_order">
                            <option value="ASC" <?= ($sortOrder ?? 'ASC') === 'ASC' ? 'selected' : '' ?>>Ascendent</option>
                            <option value="DESC" <?= ($sortOrder ?? '') === 'DESC' ? 'selected' : '' ?>>Descendent</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 d-flex">
                    <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                    <?php if (!empty($categoria) || !empty($searchTerm) || ($sortBy ?? '') !== 'nom' || ($sortOrder ?? '') !== 'ASC'): ?>
                        <a href="edit-list.php" class="btn btn-outline-secondary">Netejar filtres</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Batch edit form -->
    <form action="update-batch.php" method="POST">
        <?= CsrfMiddleware::tokenField(); ?>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Actualitzacions en Lot</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="categoria-update" class="form-label">Actualitzar Categoria</label>
                        <select class="form-select" id="categoria-update" name="updates[categoria]">
                            <option value="">No canviar</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>">
                                    <?= htmlspecialchars($cat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="estoc-adjust" class="form-label">Ajustar Estoc</label>
                        <div class="input-group">
                            <span class="input-group-text">+/-</span>
                            <input type="number" class="form-control" id="estoc-adjust" name="updates[estoc_adjust]" placeholder="0">
                        </div>
                        <small class="form-text text-muted">Introdueix un número positiu per augmentar o negatiu per disminuir</small>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="preu-adjust" class="form-label">Ajustar Preu</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="preu-adjust" name="updates[preu_adjust]" placeholder="0">
                            <select class="form-select" name="updates[preu_adjust_type]" style="max-width: 100px;">
                                <option value="absolute">€</option>
                                <option value="percent">%</option>
                            </select>
                        </div>
                        <small class="form-text text-muted">Introdueix un número positiu per augmentar o negatiu per disminuir</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th width="40">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="select-all">
                            </div>
                        </th>
                        <th>
                            <a href="?sort_by=id&sort_order=<?= ($sortBy === 'id' && $sortOrder === 'ASC') ? 'DESC' : 'ASC' ?><?= !empty($categoria) ? '&categoria=' . urlencode($categoria) : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>" class="text-white sort-link">
                                ID
                                <?php if ($sortBy === 'id'): ?>
                                    <i class="fas fa-sort-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>Imatge</th>
                        <th>
                            <a href="?sort_by=nom&sort_order=<?= ($sortBy === 'nom' && $sortOrder === 'ASC') ? 'DESC' : 'ASC' ?><?= !empty($categoria) ? '&categoria=' . urlencode($categoria) : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>" class="text-white sort-link">
                                Nom
                                <?php if ($sortBy === 'nom'): ?>
                                    <i class="fas fa-sort-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort_by=categoria&sort_order=<?= ($sortBy === 'categoria' && $sortOrder === 'ASC') ? 'DESC' : 'ASC' ?><?= !empty($categoria) ? '&categoria=' . urlencode($categoria) : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>" class="text-white sort-link">
                                Categoria
                                <?php if ($sortBy === 'categoria'): ?>
                                    <i class="fas fa-sort-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort_by=preu&sort_order=<?= ($sortBy === 'preu' && $sortOrder === 'ASC') ? 'DESC' : 'ASC' ?><?= !empty($categoria) ? '&categoria=' . urlencode($categoria) : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>" class="text-white sort-link">
                                Preu
                                <?php if ($sortBy === 'preu'): ?>
                                    <i class="fas fa-sort-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort_by=estoc&sort_order=<?= ($sortBy === 'estoc' && $sortOrder === 'ASC') ? 'DESC' : 'ASC' ?><?= !empty($categoria) ? '&categoria=' . urlencode($categoria) : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>" class="text-white sort-link">
                                Estoc
                                <?php if ($sortBy === 'estoc'): ?>
                                    <i class="fas fa-sort-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productes as $producte): ?>
                        <tr data-id="<?= $producte->id ?>">
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input product-checkbox" type="checkbox" name="product_ids[]" value="<?= $producte->id ?>">
                                </div>
                            </td>
                            <td><?= $producte->id ?></td>
                            <td>
                                <?php if ($producte->imatge): ?>
                                    <img src="<?= imageUrl($producte->imatge, 50, 50) ?>" alt="<?= htmlspecialchars($producte->nom) ?>" width="50" height="50" class="img-thumbnail">
                                <?php else: ?>
                                    <div class="bg-light text-center" style="width: 50px; height: 50px; line-height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="product-name"><?= htmlspecialchars($producte->nom) ?></td>
                            <td class="product-category"><?= htmlspecialchars($producte->categoria ?? 'Sense categoria') ?></td>
                            <td class="product-price"><?= number_format($producte->preu, 2) ?> €</td>
                            <td>
                                <span class="badge <?= $producte->estoc > 5 ? 'bg-success' : 'bg-warning' ?> stock-badge">
                                    <?= $producte->estoc ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm quick-edit-btn" data-id="<?= $producte->id ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="edit.php?id=<?= $producte->id ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="show.php?id=<?= $producte->id ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            <div>
                <button type="submit" class="btn btn-primary" id="update-selected" disabled>
                    Actualitzar Seleccionats
                </button>
                <span class="ms-3" id="selected-count">0 productes seleccionats</span>
            </div>
            
            <!-- Paginación -->
            <div class="d-flex align-items-center">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?><?= !empty($categoria) ? '&categoria=' . urlencode($categoria) : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?><?= ($sortBy ?? '') !== 'nom' ? '&sort_by=' . urlencode($sortBy) : '' ?><?= ($sortOrder ?? '') !== 'ASC' ? '&sort_order=' . urlencode($sortOrder) : '' ?>" class="btn btn-outline-primary me-2">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </a>
                <?php endif; ?>

                <span class="mx-2">Pàgina <?= $page ?> de <?= $totalPages ?></span>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?><?= !empty($categoria) ? '&categoria=' . urlencode($categoria) : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?><?= ($sortBy ?? '') !== 'nom' ? '&sort_by=' . urlencode($sortBy) : '' ?><?= ($sortOrder ?? '') !== 'ASC' ? '&sort_order=' . urlencode($sortOrder) : '' ?>" class="btn btn-outline-primary ms-2">
                        Següent <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <!-- Quick Edit Modal -->
    <div class="modal fade" id="quick-edit-modal" tabindex="-1" aria-labelledby="quickEditModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickEditModalLabel">Edició Ràpida</h5>
                    <button type="button" class="btn-close" id="close-modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="quick-edit-form">
                        <?= CsrfMiddleware::tokenField(); ?>
                        <input type="hidden" id="quick-edit-id" name="id">
                        
                        <div class="mb-3">
                            <label for="quick-edit-nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="quick-edit-nom" name="nom" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quick-edit-descripcio" class="form-label">Descripció</label>
                            <textarea class="form-control" id="quick-edit-descripcio" name="descripcio" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quick-edit-preu" class="form-label">Preu</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" id="quick-edit-preu" name="preu" required>
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quick-edit-estoc" class="form-label">Estoc</label>
                            <input type="number" class="form-control" id="quick-edit-estoc" name="estoc" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quick-edit-categoria" class="form-label">Categoria</label>
                            <select class="form-select" id="quick-edit-categoria" name="categoria">
                                <option value="">Sense categoria</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat) ?>">
                                        <?= htmlspecialchars($cat) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Guardar canvis</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Define BASE_URL for JavaScript
    const BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>/js/batch-edit.js"></script>
