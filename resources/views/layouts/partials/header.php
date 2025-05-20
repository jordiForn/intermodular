<?php use App\Core\Auth; ?>
<header class="bg-highlight fixed-top py-0" style="min-height: 150px;">
    <div class="container-fluid">
            <div>
    <h1 class="fw-bold mb-0" style="font-size:2.5rem;">
        <a href="<?= BASE_URL . '/index.php'; ?>" class="text-white text-decoration-none d-inline-block" style="font-size: inherit;">
            Tenda de Jardineria
        </a>
    </h1>
</div>

            
            <!-- CONTENEDOR DE BOTONES A LA DERECHA -->
            <div class="d-flex align-items-center ms-auto position-relative">
                <!-- Botón lupa (abre búsqueda) -->
                <button id="searchToggle" class="btn btn-link text-white fs-2 mx-3 p-0" type="button" title="Buscar">
                    <i class="fas fa-search"></i>
                </button>

                <!-- Campo de búsqueda tipo dropdown -->
                <form id="searchForm" action="<?= BASE_URL . '/productes/search.php'; ?>" method="get" class="position-absolute end-0 top-100 mt-2 bg-highlight p-2 rounded shadow d-none" style="min-width: 300px; z-index: 100;">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Buscar producte" value="<?= htmlspecialchars($q ?? ""); ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
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
    </script>
</header>
<div style="height: 150px;"></div>
