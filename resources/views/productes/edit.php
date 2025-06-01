<?php
use App\Http\Middlewares\Security\CsrfMiddleware;

$fields = ['nom', 'descripcio', 'preu', 'estoc', 'categoria', 'imatge', 'detalls'];
$values = escapeArray(formDefaults($fields, $producte ?? null));
$errors = escapeArray(session()->getFlash('errors', []));
$csrfToken = CsrfMiddleware::getToken();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow rounded-4">
                <div class="card-header bg-success text-white rounded-top-4">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Producte: <?= htmlspecialchars($producte->nom) ?>
                    </h4>
                </div>
                <div class="card-body p-4">
                    <?php if (session()->hasFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars(session()->getFlash('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors) && is_array($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $field => $fieldErrors): ?>
                <?php foreach ((array)$fieldErrors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

                    <form action="<?= BASE_URL ?>/productes/update.php" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <input type="hidden" name="id" value="<?= $producte->id ?>">

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control rounded" id="nom" name="nom" value="<?= $values['nom'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria</label>
                            <select class="form-select rounded" id="categoria" name="categoria" required>
                                <option value="">-- Selecciona una --</option>
                                <?php foreach (\App\Models\Producte::getValidCategories() as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat) ?>" <?= $values['categoria'] === $cat ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="preu" class="form-label">Preu</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" class="form-control rounded" id="preu" name="preu" value="<?= $values['preu'] ?>" required>
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estoc" class="form-label">Estoc</label>
                                <input type="number" min="0" class="form-control rounded" id="estoc" name="estoc" value="<?= $values['estoc'] ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcio" class="form-label">Descripció</label>
                            <textarea class="form-control rounded" id="descripcio" name="descripcio" rows="2"><?= $values['descripcio'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="detalls" class="form-label">Detalls</label>
                            <textarea class="form-control rounded" id="detalls" name="detalls" rows="2"><?= $values['detalls'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="imatge" class="form-label">Imatge</label>
                            <input type="text" class="form-control rounded" id="imatge" name="imatge" value="<?= $values['imatge'] ?>">
                        </div>
                        <div class="d-grid gap-2 mt-4">
    <button type="submit" class="btn btn-success btn-lg w-100 mb-2">
        <i class="fas fa-save"></i> Actualitzar Producte
    </button>
    <a href="<?= previousUrl() ?>" class="btn btn-outline-secondary btn-lg w-100">
        Cancel·lar
    </a>
</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
