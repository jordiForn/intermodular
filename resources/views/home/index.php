<?php
use App\Models\Producte;
use App\Models\Servei;
use App\Core\DB;
use App\Core\QueryBuilder;

// Get featured products (latest 6 products)
$featuredProducts = Producte::where('estoc', '>', 0)
    ->orderBy('id', 'DESC')
    ->limit(6)
    ->get();

// Get seasonal products (plants and seeds)
$seasonalProducts = Producte::where('categoria', 'Plantes i llavors')
    ->where('estoc', '>', 0)
    ->limit(4)
    ->get();

// Get services
$gardenServices = Servei::where('cat', 'jardins')->limit(3)->get();
$poolServices = Servei::where('cat', 'piscines')->limit(3)->get();
?>

<!-- Hero Section -->
<section class="bg-success bg-gradient py-5 mb-5 mt-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                <h1 class="display-4 fw-bold mb-3">Benvinguts a Intermodular</h1>
                <p class="lead mb-2">El teu jardí perfecte comença aquí</p>
                <p class="mb-4">Descobreix la nostra àmplia selecció de plantes, ferramentes i serveis professionals per crear l'espai verd dels teus somnis.</p>
                <div class="mb-4">
                    <a href="<?= BASE_URL ?>/productes/index.php" class="btn btn-primary btn-lg me-2">Explorar Productes</a>
                    <a href="<?= BASE_URL ?>/serveis/index.php" class="btn btn-outline-light btn-lg">Veure Serveis</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="<?= BASE_URL ?>/images/begonia.jpg" alt="Jardí beautiful" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="py-5 bg-light mb-5 mt-5">
    <div class="container">
        <h2 class="text-center mb-5 text-success fw-bold">Categories Destacades</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <div class="display-3 mb-3">🌱</div>
                        <h3 class="card-title">Plantes i Llavors</h3>
                        <p class="card-text">Varietat de plantes per a tots els gustos</p>
                        <a href="<?= BASE_URL ?>/productes/index.php#plantes" class="btn btn-success">Descobrir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <div class="display-3 mb-3">🛠️</div>
                        <h3 class="card-title">Ferramentes</h3>
                        <p class="card-text">Eines professionals per al teu jardí</p>
                        <a href="<?= BASE_URL ?>/productes/index.php#ferramentes" class="btn btn-success">Pròximam</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <div class="display-3 mb-3">🌍</div>
                        <h3 class="card-title">Terra i Adobs</h3>
                        <p class="card-text">Nutrients per a plantes saludables</p>
                        <a href="<?= BASE_URL ?>/productes/index.php?page=2" class="btn btn-success">Descobrir</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Seasonal Promotion -->
<section class="py-5 bg-white mb-5 mt-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <span class="badge bg-success mb-3">Oferta de Temporada</span>
                <h2 class="fw-bold">Primavera al teu Jardí</h2>
                <p>Aprofita els nostres descomptes especials en plantes de temporada. Transforma el teu espai amb les millors varietats de primavera.</p>
                <ul class="list-unstyled mb-4">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Enviament gratuït en comandes +50€</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Garantia de qualitat</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Assessorament expert</li>
                </ul>
                <a href="<?= BASE_URL ?>/productes/index.php" class="btn btn-outline-success">Veure Ofertes</a>
            </div>
            <div class="col-md-6 text-center">
                <img src="<?= BASE_URL ?>/images/gerani.jpg" alt="Plantes de temporada" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-light mb-5 mt-5">
    <div class="container">
        <h2 class="text-center mb-5 text-success fw-bold">Productes Destacats</h2>
        <div class="row g-4">
            <?php foreach ($featuredProducts as $producte): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= BASE_URL ?>/images/<?= htmlspecialchars($producte->imatge) ?>" class="card-img-top" alt="<?= htmlspecialchars($producte->nom) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($producte->nom) ?></h5>
                            <p class="card-text text-muted mb-1"><?= htmlspecialchars($producte->categoria) ?></p>
                            <div class="mb-2 fw-bold text-success"><?= number_format($producte->preu, 2, ",", ".") ?>€</div>
                            <a href="<?= BASE_URL ?>/productes/show.php?id=<?= $producte->id ?>" class="btn btn-outline-success mt-auto">Veure Detalls</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/productes/index.php" class="btn btn-success">Veure Tots els Productes</a>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5 bg-white mb-5 mt-5">
    <div class="container">
        <h2 class="text-center mb-5 text-success fw-bold">Els Nostres Serveis</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="display-5 me-3">🏡</div>
                            <h3 class="mb-0">Serveis per a Jardins</h3>
                        </div>
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach ($gardenServices as $servei): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($servei->nom) ?>
                                    <span class="badge bg-success rounded-pill"><?= number_format($servei->preu_base, 2, ",", ".") ?>€/h</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="<?= BASE_URL ?>/serveis/jardins.php" class="btn btn-outline-success">Veure Més</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="display-5 me-3">🏊</div>
                            <h3 class="mb-0">Serveis per a Piscines</h3>
                        </div>
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach ($poolServices as $servei): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($servei->nom) ?>
                                    <span class="badge bg-success rounded-pill"><?= number_format($servei->preu_base, 2, ",", ".") ?>€/h</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="<?= BASE_URL ?>/serveis/piscines.php" class="btn btn-outline-success">Veure Més</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5 bg-success bg-gradient text-white mb-5 mt-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 class="fw-bold">Mantén-te Informat</h2>
                <p>Subscriu-te al nostre butlletí per rebre consells de jardineria, ofertes exclusives i novetats.</p>
            </div>
            <div class="col-md-6">
                <form action="<?= BASE_URL ?>/contact/store.php" method="POST" class="d-flex flex-column flex-sm-row gap-3">
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="El teu correu electrònic" required>
                    <button type="submit" class="btn btn-light btn-lg fw-bold">Subscriure's</button>
                </form>
            </div>
        </div>
    </div>
</section>