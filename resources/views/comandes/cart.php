<?php use App\Core\Auth; ?>

<div class="container mt-4">
    <h1 class="mb-4">El meu carret</h1>

    <!-- Mostrar mensajes de error y éxito si existen -->
    <?php include __DIR__ . '/../partials/message.php'; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Productes al carret</h5>
                </div>
                <div class="card-body">
                    <div id="cart-items-container">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Carregant...</span>
                            </div>
                            <p class="mt-2">Carregant el carret...</p>
                        </div>
                    </div>
                    
                    <div id="empty-cart-message" class="text-center py-4" style="display: none;">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5>El teu carret està buit</h5>
                        <p>Afegeix productes al carret per a continuar amb la compra.</p>
                        <a href="<?= BASE_URL ?>/productes/index.php" class="btn btn-primary mt-2">
                            Veure productes
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Resum de la comanda</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="cart-subtotal">0.00 €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>IVA (21%):</span>
                        <span id="cart-tax">0.00 €</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong id="cart-total">0.00 €</strong>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button id="checkout-button" class="btn btn-success" disabled>
                            Finalitzar compra
                        </button>
                        <a href="<?= BASE_URL ?>/productes/index.php" class="btn btn-outline-secondary">
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
    const cartItemsContainer = document.getElementById('cart-items-container');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const cartSubtotal = document.getElementById('cart-subtotal');
    const cartTax = document.getElementById('cart-tax');
    const cartTotal = document.getElementById('cart-total');
    const checkoutButton = document.getElementById('checkout-button');
    
    // Load cart from localStorage
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    // Update checkout button
    checkoutButton.addEventListener('click', function() {
        <?php if (Auth::check()): ?>
            window.location.href = '<?= BASE_URL ?>/comandes/checkout.php';
        <?php else: ?>
            window.location.href = '<?= BASE_URL ?>/auth/show-login.php?redirect_to=<?= urlencode(BASE_URL . "/comandes/checkout.php") ?>';
        <?php endif; ?>
    });
    
    // Display cart items or empty cart message
    if (cart.length === 0) {
        cartItemsContainer.style.display = 'none';
        emptyCartMessage.style.display = 'block';
        checkoutButton.disabled = true;
    } else {
        // Create cart items table
        let tableHTML = `
            <table class="table">
                <thead>
                    <tr>
                        <th>Producte</th>
                        <th>Preu</th>
                        <th>Quantitat</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        let subtotal = 0;
        
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            tableHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.price.toFixed(2)} €</td>
                    <td>
                        <div class="input-group input-group-sm" style="width: 120px;">
                            <button class="btn btn-outline-secondary quantity-btn" data-action="decrease" data-index="${index}">-</button>
                            <input type="text" class="form-control text-center" value="${item.quantity}" readonly>
                            <button class="btn btn-outline-secondary quantity-btn" data-action="increase" data-index="${index}">+</button>
                        </div>
                    </td>
                    <td>${itemTotal.toFixed(2)} €</td>
                    <td>
                        <button class="btn btn-sm btn-danger remove-item" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tableHTML += `
                </tbody>
            </table>
        `;
        
        cartItemsContainer.innerHTML = tableHTML;
        
        // Calculate and display totals
        const tax = subtotal * 0.21;
        const total = subtotal + tax;
        
        cartSubtotal.textContent = subtotal.toFixed(2) + ' €';
        cartTax.textContent = tax.toFixed(2) + ' €';
        cartTotal.textContent = total.toFixed(2) + ' €';
        
        // Enable checkout button if cart is not empty
        checkoutButton.disabled = false;
        
        // Add event listeners for quantity buttons
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                const action = this.getAttribute('data-action');
                
                if (action === 'increase') {
                    cart[index].quantity += 1;
                } else if (action === 'decrease') {
                    if (cart[index].quantity > 1) {
                        cart[index].quantity -= 1;
                    }
                }
                
                // Update localStorage
                localStorage.setItem('cart', JSON.stringify(cart));
                
                // Reload the page to reflect changes
                location.reload();
            });
        });
        
        // Add event listeners for remove buttons
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                
                // Remove item from cart
                cart.splice(index, 1);
                
                // Update localStorage
                localStorage.setItem('cart', JSON.stringify(cart));
                
                // Reload the page to reflect changes
                location.reload();
            });
        });
    }
});
</script>
