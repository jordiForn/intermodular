<?php require_once __DIR__ . '/../../layouts/partials/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h2 class="mb-0"><i class="fas fa-search me-2"></i>Error 404: Página no encontrada</h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="<?= asset('images/error-404.svg') ?>" alt="Página no encontrada" class="img-fluid" style="max-height: 200px;">
                    </div>
                    
                    <h4 class="card-title">Lo sentimos, no podemos encontrar lo que estás buscando</h4>
                    <p class="card-text">La página o recurso solicitado no existe o ha sido movido a otra ubicación.</p>
                    
                    <hr>
                    
                    <h5>Posibles causas:</h5>
                    <ul>
                        <li>La URL puede contener un error tipográfico</li>
                        <li>El enlace que siguió puede estar desactualizado</li>
                        <li>El recurso solicitado puede haber sido eliminado</li>
                    </ul>
                    
                    <h5>Sugerencias:</h5>
                    <ul>
                        <li>Verifique que la URL esté escrita correctamente</li>
                        <li>Utilice la navegación del sitio o el buscador para encontrar lo que necesita</li>
                        <li>Vuelva a la página de inicio y comience de nuevo</li>
                    </ul>
                    
                    <div class="mt-4 text-center">
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
