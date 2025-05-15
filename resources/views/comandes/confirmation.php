<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Comanda confirmada</h4>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                    
                    <h2 class="mb-4">Gràcies per la teva comanda!</h2>
                    
                    <p class="lead">La teva comanda ha estat processada correctament.</p>
                    
                    <div class="alert alert-info mt-3">
                        <p class="mb-0">Número de comanda: <strong><?= $comanda->id ?></strong></p>
                        <p class="mb-0">Data: <strong><?= $comanda->data_comanda ?></strong></p>
                        <p class="mb-0">Total: <strong><?= number_format($comanda->total, 2) ?> €</strong></p>
                    </div>
                    
                    <p>Rebràs un correu electrònic amb els detalls de la teva comanda.</p>
                    
                    <div class="mt-4">
                        <a href="<?= BASE_URL ?>/comandes/my-orders.php" class="btn btn-primary">
                            Veure les meves comandes
                        </a>
                        <a href="<?= BASE_URL ?>/productes/index.php" class="btn btn-outline-secondary ms-2">
                            Continuar comprant
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Clear cart after successful order
    localStorage.removeItem('cart');
});
</script>
