<?php require_once __DIR__ . '/../../layouts/partials/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h2 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Error 400: Bad Request</h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="<?= asset('images/error-400.svg') ?>" alt="Bad Request" class="img-fluid" style="max-height: 200px;">
                    </div>
                    
                    <h4 class="card-title">Lo sentimos, no podemos procesar esta solicitud</h4>
                    <p class="card-text">La solicitud enviada contiene errores o datos incorrectos que no pueden ser procesados por el servidor.</p>
                    
                    <hr>
                    
                    <h5>Posibles causas:</h5>
                    <ul>
                        <li>Datos de formulario incorrectos o incompletos</li>
                        <li>Parámetros de URL mal formados</li>
                        <li>Solicitud con sintaxis incorrecta</li>
                    </ul>
                    
                    <h5>Sugerencias:</h5>
                    <ul>
                        <li>Verifique que todos los campos requeridos estén completos</li>
                        <li>Asegúrese de que los datos enviados tengan el formato correcto</li>
                        <li>Intente navegar a través de los enlaces de la aplicación en lugar de modificar la URL manualmente</li>
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
                    <small>Si el problema persiste, por favor contacte con el administrador del sistema.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/partials/footer.php'; ?>
