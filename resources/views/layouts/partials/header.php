<?php use App\Core\Auth; ?>
<header class="bg-dark text-white pt-2 pb-0">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-3">
                <h3>Vota Películas</h3>
            </div>
            <div class="col-6 d-flex justify-content-center">
                <form action="<?= BASE_URL . '/peliculas/search.php'; ?>" method="get" class="d-flex w-100" style="max-width: 500px;">
                    <input type="text" name="q" class="form-control me-2" placeholder="Buscar película" value="<?= htmlspecialchars($q ?? ""); ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Área de identificación y carrito -->
            <div class="col-3 text-end">
                <?php if (!Auth::check()): ?>
                    <a href="<?= BASE_URL . '/auth/show-login.php'; ?>" class="btn btn-outline-light">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                <?php else: ?>
                    <span class="me-2">
                        <i class="fas fa-user"></i> <?= htmlspecialchars(Auth::user()['nombre']) ?>
                    </span>
                    <a href="<?= BASE_URL . '/auth/logout.php'; ?>" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Barra de navegación -->
    <nav class="mt-2" style="background-color: #343a40;">
        <ul class="nav">
            <li class="nav-item">
                <a
                    class="nav-link <?= request()->routeIs('/peliculas/index.php') ? 'bg-white text-dark rounded-top' : 'text-white' ?>" 
                    href="<?= BASE_URL . '/peliculas/index.php'; ?>">
                    Películas
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link <?= request()->routeIs('/directores/index.php') ? 'bg-white text-dark rounded-top' : 'text-white' ?>" 
                    href="<?= BASE_URL . '/directores/index.php'; ?>">
                    Directores
                </a>
            </li>
            <?php if(Auth::check()): ?>
                <li class="nav-item">
                    <a 
                        class="nav-link <?= request()->routeIs('/usuarios/my-votes.php') ? 'bg-white text-dark rounded-top' : 'text-white' ?>"     
                        href="<?= BASE_URL . '/usuarios/my-votes.php'; ?>">
                        Ver mis votos
                    </a>
                </li>
            <?php endif; ?>
            <?php if(Auth::check() && Auth::role() === 'admin'): ?>
                <li class="nav-item">
                    <a 
                        class="nav-link <?= request()->routeIs('/peliculas/create.php') ? 'bg-white text-dark rounded-top' : 'text-warning' ?>"     
                        href="<?= BASE_URL . '/peliculas/create.php'; ?>">
                        Crear película
                    </a>
                </li>
                <li class="nav-item">
                    <a 
                        class="nav-link <?= request()->routeIs('/directores/create.php') ? 'bg-white text-dark rounded-top' : 'text-warning' ?>"     
                        href="<?= BASE_URL . '/directores/create.php'; ?>">
                        Crear director
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </nav>
</header>
