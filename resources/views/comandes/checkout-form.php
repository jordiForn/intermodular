<?php 
use App\Core\Auth; 
use App\Http\Middlewares\Security\CsrfMiddleware;
?>

<div class="container mt-4">
    <h1 class="mb-4">Finalitzar compra</h1>

    <!-- Mostrar mensajes de error y éxito si existen -->
    <?php include __DIR__ . '/../partials/message.php'; ?>
    <?php include __DIR__ . '/../partials/errors.php'; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Dades d'enviament</h5>
                </div>
                <div class="card-body">
                    <form id="checkout-form" action="<?= BASE_URL ?>/comandes/process-order.php" method="POST">
                        <?= CsrfMiddleware::tokenField(); ?>
                        
                        <input type="hidden" name="cart_json" id="cart_json">
                        <input type="hidden" id="cart-items" name="cart_items" value="">
                        <input type="hidden" id="cart-total" name="total" value="">
                        
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" value="<?= htmlspecialchars($client->nom . ' ' . $client->cognom) ?>" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($client->user()->email) ?>" readonly>
                        </div>
                        
                        
                        <div class="mb-3">
    <label for="direccio_enviament" class="form-label">Direcció d'enviament</label>
    <textarea class="form-control <?= isset($errors['direccio_enviament']) ? 'is-invalid' : '' ?>" id="direccio_enviament" name="direccio_enviament" rows="3" required><?= htmlspecialchars($oldInput['direccio_enviament'] ?? '') ?></textarea>
    <?php if (isset($errors['direccio_enviament'])): ?>
        <div class="invalid-feedback"><?= $errors['direccio_enviament'] ?></div>
    <?php endif; ?>
</div>
                        
                        <div class="mb-3">
    <label for="missatge" class="form-label">Missatge (opcional)</label>
    <textarea class="form-control" id="missatge" name="missatge" rows="2"></textarea>
</div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success" id="submit-order">
                                Confirmar comanda
                            </button>
                            <a href="<?= BASE_URL ?>/comandes/cart.php" class="btn btn-outline-secondary">
                                Tornar al carret
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Resum de la comanda</h5>
                </div>
                <div class="card-body">
                    <div id="cart-summary">
                        <div class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Carregant...</span>
                            </div>
                            <p class="mt-2">Carregant el resum...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartSummary = document.getElementById('cart-summary');
    const cartItemsInput = document.getElementById('cart-items');
    const cartTotalInput = document.getElementById('cart-total');
    const checkoutForm = document.getElementById('checkout-form');
    
    // Load cart from localStorage
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    // Redirect to cart page if cart is empty
    if (cart.length === 0) {
        window.location.href = '<?= BASE_URL ?>/comandes/cart.php';
    }
    
    // Set cart items and total in hidden inputs
    cartItemsInput.value = JSON.stringify(cart);
    
    // Display cart summary
    let summaryHTML = '';
    let subtotal = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        
        summaryHTML += `
            <div class="d-flex justify-content-between mb-2">
                <span>${item.name} x ${item.quantity}</span>
                <span>${itemTotal.toFixed(2)} €</span>
            </div>
        `;
    });
    
    const tax = subtotal * 0.21;
    const total = subtotal + tax;
    
    summaryHTML += `
        <hr>
        <div class="d-flex justify-content-between mb-2">
            <span>Subtotal:</span>
            <span>${subtotal.toFixed(2)} €</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span>IVA (21%):</span>
            <span>${tax.toFixed(2)} €</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between mb-3">
            <strong>Total:</strong>
            <strong>${total.toFixed(2)} €</strong>
        </div>
    `;
    
    cartSummary.innerHTML = summaryHTML;
    cartTotalInput.value = total.toFixed(2);
    
    // Form submission
    checkoutForm.addEventListener('submit', function(event) {
        // Additional validation can be added here
        
        // Clear cart after successful submission
        // This will be done after the order is processed successfully
    });
});
</script>
