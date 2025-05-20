<?php use App\Core\Auth; ?>
<header class="bg-highlight fixed-top py-0">
    <div class="container-fluid d-flex align-items-center justify-content-between" style="min-height: 150px;">
            <div>
                <h1 class="fw-bold mb-0" style="font-size:2.5rem;">
                    <a href="<?= BASE_URL . '/index.php'; ?>" class="text-white text-decoration-none d-inline-block" style="font-size: inherit;">
                        Tenda de Jardineria
                    </a>
                </h1>
            </div>
            <div class="d-flex align-items-center ms-auto position-relative">
                <!-- Botón lupa (abre búsqueda) -->
                <button id="searchToggle" class="btn btn-link text-white fs-2 mx-3 p-0" type="button" title="Buscar">
                    <i class="fas fa-search"></i>
                </button>

                <!-- Campo de búsqueda tipo dropdown -->
                <form id="searchForm" action="<?= BASE_URL . '/productes/search.php'; ?>" method="get" class="position-absolute end-0 top-100 mt-2 bg-highlight p-2 rounded shadow d-none" style="min-width: 300px; z-index: 100;">
                    <div class="input-group position-relative">
                        <input type="text" name="q" id="live-search-input" class="form-control" placeholder="Buscar producte" autocomplete="off" value="<?= htmlspecialchars($q ?? ""); ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <div id="live-search-results" class="list-group position-absolute w-100" style="top: 100%; left: 0; z-index: 200;"></div>
                    </div>
                </form>

                <!-- Botón contacto (enviar mensaje) -->
                <a href="<?= BASE_URL . '/contact/show-form.php'; ?>" class="btn btn-link text-white fs-2 mx-3 p-0" title="Enviar mensaje">
                    <i class="fas fa-envelope"></i>
                </a>

                <!-- Botón usuario/login (Clients) -->
                <?php if (!Auth::check()): ?>
                    <a href="<?= BASE_URL . '/clients/index.php'; ?>" class="btn btn-link text-white fs-2 mx-3 p-0" title="Clients">
                        <i class="fas fa-user"></i>
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL . '/clients/index.php'; ?>" class="btn btn-link text-white fs-2 mx-3 p-0" title="Clients">
                        <i class="fas fa-user"></i>
                    </a>
                <?php endif; ?>

                <!-- Botón carrito -->
                <a href="<?= BASE_URL . '/comandes/cart.php'; ?>" class="btn btn-link text-white fs-2 mx-3 p-0 position-relative" title="Carret">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </div>

        <script>
            // Mostrar/ocultar campo de búsqueda tipo dropdown
            document.addEventListener('DOMContentLoaded', function() {
                var searchToggle = document.getElementById('searchToggle');
                var searchForm = document.getElementById('searchForm');
                document.addEventListener('click', function(e) {
                    if (searchForm && !searchForm.classList.contains('d-none') && !searchForm.contains(e.target) && e.target !== searchToggle) {
                        searchForm.classList.add('d-none');
                    }
                });
                searchToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    searchForm.classList.toggle('d-none');
                    if (!searchForm.classList.contains('d-none')) {
                        searchForm.querySelector('input').focus();
                    }
                });
            });

            // Búsqueda en vivo
            const searchInput = document.getElementById('live-search-input');
            const resultsBox = document.getElementById('live-search-results');
            let searchTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    if (query.length < 2) {
                        resultsBox.innerHTML = '';
                        resultsBox.style.display = 'none';
                        return;
                    }
                    searchTimeout = setTimeout(() => {
                        fetch('<?= BASE_URL ?>/productes/search.php?q=' + encodeURIComponent(query))
                            .then(res => res.json())
                            .then(data => {
                                if (Array.isArray(data) && data.length > 0) {
                                    resultsBox.innerHTML = data.map(item =>
                                        `<a href=\"<?= BASE_URL ?>/productes/show.php?id=${item.id}\" class=\"list-group-item list-group-item-action\">${item.nom}</a>`
                                    ).join('');
                                    resultsBox.style.display = 'block';
                                } else {
                                    resultsBox.innerHTML = '<div class="list-group-item">No s\'han trobat resultats</div>';
                                    resultsBox.style.display = 'block';
                                }
                            })
                            .catch(() => {
                                resultsBox.innerHTML = '<div class="list-group-item">Error en la cerca</div>';
                                resultsBox.style.display = 'block';
                            });
                    }, 250);
                });
                // Ocultar resultados al perder foco
                searchInput.addEventListener('blur', function() {
                    setTimeout(() => { resultsBox.style.display = 'none'; }, 200);
                });
                searchInput.addEventListener('focus', function() {
                    if (resultsBox.innerHTML) resultsBox.style.display = 'block';
                });
            }
        </script>
    </div>
</header>
<div style="height: 50px;"></div>
