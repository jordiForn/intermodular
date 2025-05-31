<?php require_once __DIR__ . '/../../layouts/partials/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h2 class="mb-0"><i class="fas fa-lock me-2"></i>Error 403: Acceso prohibido</h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="<?= asset('images/error-403.svg') ?>" alt="Acceso prohibido" class="img-fluid" style="max-height: 200px;">
                    </div>
                    
                    <h4 class="card-title">Lo sentimos, no tienes permiso para acceder a este recurso</h4>
                    <p class="card-text">No dispones de los permisos necesarios para ver esta página o realizar esta acción.</p>
                    
                    <hr>
                    
                    <h5>Posibles causas:</h5>
                    <ul>
                        <li>Tu cuenta no tiene los privilegios necesarios</li>
                        <li>Es necesario iniciar sesión para acceder a este contenido</li>
                        <li>Estás intentando acceder a un área restringida</li>
                    </ul>
                    
                    <h5>Sugerencias:</h5>
                    <ul>
                        <li>Inicia sesión si aún no lo has hecho</li>
                        <li>Contacta con un administrador si necesitas acceso a este recurso</li>
                        <li>Vuelve a la página anterior o a la página de inicio</li>
                    </ul>
                    
                    <div class="mt-4 text-center">
                        <?php if (!auth()->check()): ?>
                            <a href="<?= url('/auth/show-login.php') ?>" class="btn btn-success me-2">
                                <i class="fas fa-sign-in-alt me-1"></i> Iniciar sesión
                            </a>
                        <?php endif; ?>
                        <a href="<?= url('/') ?>" class="btn btn-primary me-2">
                            <i class="fas fa-home me-1"></i> Volver al inicio
                        </a>
                        <button onclick="window.history.back()" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Volver atrás
                        </button>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <small>Si cree que esto es un error, por favor contacte con el administrador del sistema.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/partials/footer.php'; ?>
