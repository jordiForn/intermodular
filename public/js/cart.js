// Function to add items to cart
console.log("cart.js loaded");

// Function to update cart count in UI
function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

  // Update any cart count elements
  const cartCountElements = document.querySelectorAll(
    ".cart-count, #cart-count"
  );
  cartCountElements.forEach((element) => {
    element.textContent = totalItems;
  });
}

// Function to render cart in cart page
function renderCartPage() {
  const cartItemsContainer = document.getElementById("cart-items-container");
  const emptyCartMessage = document.getElementById("empty-cart-message");
  const cartSubtotal = document.getElementById("cart-subtotal");
  const cartTax = document.getElementById("cart-tax");
  const cartTotal = document.getElementById("cart-total");
  const checkoutButton = document.getElementById("checkout-button");

  if (!cartItemsContainer) return; // Only run on cart page

  // Load cart from localStorage
  const cart = JSON.parse(localStorage.getItem("cart") || "[]");

  // Display cart items or empty cart message
  if (cart.length === 0) {
    cartItemsContainer.style.display = "none";
    if (emptyCartMessage) emptyCartMessage.style.display = "block";
    if (checkoutButton) checkoutButton.disabled = true;
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
              <input type="text" class="form-control text-center" value="${
                item.quantity
              }" readonly>
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
    if (cartSubtotal) cartSubtotal.textContent = subtotal.toFixed(2) + " €";
    if (cartTax) cartTax.textContent = tax.toFixed(2) + " €";
    if (cartTotal) cartTotal.textContent = total.toFixed(2) + " €";
    if (checkoutButton) checkoutButton.disabled = false;
    // Add event listeners for quantity buttons
    document.querySelectorAll(".quantity-btn").forEach((button) => {
      button.addEventListener("click", function () {
        const index = parseInt(this.getAttribute("data-index"));
        const action = this.getAttribute("data-action");
        if (action === "increase") {
          if (cart[index].quantity < cart[index].stock) {
            cart[index].quantity += 1;
          }
        } else if (action === "decrease") {
          if (cart[index].quantity > 1) {
            cart[index].quantity -= 1;
          }
        }
        localStorage.setItem("cart", JSON.stringify(cart));
        renderCartPage();
        updateCartCount();
      });
    });
    // Add event listeners for remove buttons
    document.querySelectorAll(".remove-item").forEach((button) => {
      button.addEventListener("click", function () {
        const index = parseInt(this.getAttribute("data-index"));
        cart.splice(index, 1);
        localStorage.setItem("cart", JSON.stringify(cart));
        renderCartPage();
        updateCartCount();
      });
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  updateCartCount();
  renderCartPage();
});

function goToCheckout() {
  const cart = JSON.parse(localStorage.getItem("cart") || "[]");
  fetch("/comandes/sync-cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "cart_items=" + encodeURIComponent(JSON.stringify(cart)),
  })
    .then((res) => res.text())
    .then(() => {
      window.location.href = "/comandes/checkout.php";
    });
}
