<?php use App\Core\Auth; ?>
<header class="bg-dark text-white pt-2 pb-0">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-3">
                <h3>Jardineria</h3>
            </div>
            <div class="col-6 d-flex justify-content-center">
                <form action="<?= BASE_URL . '/productes/search.php'; ?>" method="get" class="d-flex w-100" style="max-width: 500px;">
                    <input type="text" name="q" class="form-control me-2" placeholder="Buscar producte" value="<?= htmlspecialchars($q ?? ""); ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Área de identificación y carrito -->
            <div class="col-3 text-end">
                <a href="<?= BASE_URL . '/comandes/cart.php'; ?>" class="btn btn-outline-light me-2 tooltip-container">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="tooltip-text">0 ítems - 0,00€</span>
                </a>
                
                <?php if (!Auth::check()): ?>
                    <a href="<?= BASE_URL . '/auth/show-login.php'; ?>" class="btn btn-outline-light">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                <?php else: ?>
                    <span class="me-2">
                        <i class="fas fa-user"></i> <?= htmlspecialchars(Auth::user()->nom) ?>
                    </span>
                    <a href="<?= BASE_URL . '/auth/logout.php'; ?>" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Barra de navegación -->
    <nav class="mt-2">
        <ul class="nav">
            <li class="nav-item">
                <a
                    class="nav-link <?= request()->routeIs('/productes/index.php') ? 'bg-white text-dark rounded-top' : 'text-white' ?>" 
                    href="<?= BASE_URL . '/productes/index.php'; ?>">
                    Productes
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link <?= request()->routeIs('/serveis/index.php') ? 'bg-white text-dark rounded-top' : 'text-white' ?>" 
                    href="<?= BASE_URL . '/serveis/index.php'; ?>">
                    Serveis
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link <?= request()->routeIs('/clients/index.php') ? 'bg-white text-dark rounded-top' : 'text-white' ?>" 
                    href="<?= BASE_URL . '/clients/index.php'; ?>">
                    Clients
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link <?= request()->routeIs('/contact/show-form.php') ? 'bg-white text-dark rounded-top' : 'text-white' ?>" 
                    href="<?= BASE_URL . '/contact/show-form.php'; ?>">
                    Contacte
                </a>
            </li>
            <?php if(Auth::check()): ?>
                <li class="nav-item">
                    <a 
                        class="nav-link <?= request()->routeIs('/comandes/my-orders.php') ? 'bg-white text-dark rounded-top' : 'text-white' ?>"     
                        href="<?= BASE_URL . '/comandes/my-orders.php'; ?>">
                        Les meves comandes
                    </a>
                </li>
                <li class="nav-item">
                    <a 
                        class="nav-link <?= request()->routeIs('/comandes/cart.php') ? 'bg-white text-dark rounded-top' : 'text-white' ?>"     
                        href="<?= BASE_URL . '/comandes/cart.php'; ?>">
                        Carret
                    </a>
                </li>
            <?php endif; ?>
            <?php if(Auth::check() && Auth::role() === 'admin'): ?>
                <li class="nav-item">
                    <a 
                        class="nav-link <?= request()->routeIs('/productes/create.php') ? 'bg-white text-dark rounded-top' : 'text-warning' ?>"     
                        href="<?= BASE_URL . '/productes/create.php'; ?>">
                        Crear producte
                    </a>
                </li>
                <li class="nav-item">
                    <a 
                        class="nav-link <?= request()->routeIs('/clients/create.php') ? 'bg-white text-dark rounded-top' : 'text-warning' ?>"     
                        href="<?= BASE_URL . '/clients/create.php'; ?>">
                        Crear client
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
