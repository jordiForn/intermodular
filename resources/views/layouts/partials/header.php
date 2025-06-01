<?php use App\Core\Auth; ?>
<header class="bg-highlight fixed-top py-0">
    <div class="container-fluid d-flex align-items-center justify-content-between" style="min-height: 130px;">
            <div>
                <h1 class="fw-bold mb-0" style="font-size:2.5rem;">
                    <?php if (Auth::check() && Auth::isAdmin()): ?>
                        <!-- Admin users go to admin dashboard -->
                        <a href="<?= BASE_URL . '/admin/'; ?>" class="text-white text-decoration-none d-inline-block" style="font-size: inherit;">
                            Tenda de Jardineria
                        </a>
                    <?php else: ?>
                        <!-- Regular users and guests go to public home -->
                        <a href="<?= BASE_URL . '/index.php'; ?>" class="text-white text-decoration-none d-inline-block" style="font-size: inherit;">
                            Tenda de Jardineria
                        </a>
                    <?php endif; ?>
                </h1>
            </div>
            
            <!-- Saludo de bienvenida -->
            <?php if (session()->get('user_id')): ?>
                <div class="flex-grow-1 d-flex justify-content-center">
                    <?php if (Auth::check() && Auth::isAdmin()): ?>
                        <span class="text-white fw-semibold" style="font-size:1.3rem;">
                            <i class="fas fa-shield-alt me-2"></i>Panel d'Administració - ¡Benvingut, <?= htmlspecialchars(session()->get('nom_real')) ?>!
                        </span>
                    <?php else: ?>
                        <span class="text-white fw-semibold" style="font-size:1.3rem;">¡Benvingut, <?= htmlspecialchars(session()->get('nom_real')) ?>!</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="d-flex align-items-center ms-auto position-relative">
                <?php if (Auth::check() && Auth::isAdmin()): ?>
                    <!-- Admin header - only logout button -->
                    <a href="<?= BASE_URL . '/auth/logout.php'; ?>" class="btn btn-outline-light btn-lg px-4 py-2 me-3" title="Tancar sessió">
                        <i class="fas fa-sign-out-alt me-2"></i>Tancar Sessió
                    </a>
                    
                    <!-- Optional: Quick link to public site for admins -->
                    <a href="<?= BASE_URL . '/index.php'; ?>" class="btn btn-link text-white fs-5 mx-2 p-0" title="Veure lloc públic">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    
                <?php else: ?>
                    <!-- Regular user/guest header - full functionality -->
                    
                    <!-- Botón lupa (abre búsqueda) -->
                    <a id="searchToggle" class="btn btn-link text-white fs-2 mx-3 p-0 btn-animate-up" href="#" title="Buscar">
                        <i class="fas fa-search"></i>
                    </a>

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

                    <!-- Botón usuario/login o cerrar sesión -->
                    <?php if (session()->get('user_id')): ?>
                        <!-- Botón cerrar sesión -->
                        <a href="<?= BASE_URL . '/auth/logout.php'; ?>" class="btn btn-link text-white fs-2 mx-3 p-0" title="Tancar sessió">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    <?php else: ?>
                        <!-- Botón iniciar sesión -->
                        <a href="<?= BASE_URL . '/auth/show-login.php'; ?>" class="btn btn-link text-white fs-2 mx-3 p-0" title="Iniciar sessió">
                            <i class="fas fa-user"></i>
                        </a>
                    <?php endif; ?>

                    <!-- Botón carrito -->
                    <a href="<?= BASE_URL . '/comandes/cart.php'; ?>" class="btn btn-link text-white fs-2 mx-3 p-0 position-relative" title="Carret">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                <?php endif; ?>
            </div>
    </div>
</header>

<!-- Spacer div to account for fixed header -->
<div style="height: 50px;"></div>

<?php if (!Auth::check() || !Auth::isAdmin()): ?>
<!-- Search functionality script - only load for non-admin users -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var searchToggle = document.getElementById('searchToggle');
        var searchForm = document.getElementById('searchForm');
        
        if (searchToggle && searchForm) {
            document.addEventListener('click', function(e) {
                if (searchForm && !searchForm.classList.contains('d-none') && !searchForm.contains(e.target) && e.target !== searchToggle) {
                    searchForm.classList.add('d-none');
                }
            });
            
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                searchForm.classList.toggle('d-none');
                if (!searchForm.classList.contains('d-none')) {
                    searchForm.querySelector('input').focus();
                }
            });

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
                                        `<a href="<?= BASE_URL ?>/productes/show.php?id=${item.id}" class="list-group-item list-group-item-action">${item.nom}</a>`
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
                
                searchInput.addEventListener('blur', function() {
                    setTimeout(() => { resultsBox.style.display = 'none'; }, 200);
                });
                
                searchInput.addEventListener('focus', function() {
                    if (resultsBox.innerHTML) resultsBox.style.display = 'block';
                });
            }
        }
    });
</script>
<?php endif; ?>
