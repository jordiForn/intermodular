/**
 * Initialize cart functionality
 */
function initializeCart() {
  console.log("initializeCart ejecutado");
  // Update cart count on page load
  updateCartCount();

  // Initialize tooltips for cart buttons
  initializeTooltips();

  // Add event listeners to cart buttons
  const addToCartButtons = document.querySelectorAll(
    ".tooltip-container button"
  );

  addToCartButtons.forEach((button) => {
    if (!button.classList.contains("cart-listener-added")) {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        addToCart(
          this.getAttribute("data-name"),
          Number.parseFloat(this.getAttribute("data-price")),
          Number.parseInt(this.getAttribute("data-id")),
          Number.parseInt(this.getAttribute("data-stock"))
        );
      });
      button.classList.add("cart-listener-added");
    }
  });
}

/**
 * Update cart count in header
 */
function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
  const cartCountElement = document.getElementById("cart-count");
  if (cartCountElement) {
    cartCountElement.textContent = totalItems;
  }
}

/**
 * Initialize tooltips for cart buttons
 */
function initializeTooltips() {
  const tooltipContainers = document.querySelectorAll(".tooltip-container");
  tooltipContainers.forEach((container) => {
    const button = container.querySelector("button");
    const tooltip = container.querySelector(".tooltip-text");

    if (button && tooltip) {
      button.addEventListener("mouseover", () => {
        // Get cart data from localStorage
        const cart = JSON.parse(localStorage.getItem("cart")) || [];
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const totalPrice = cart.reduce(
          (sum, item) => sum + item.price * item.quantity,
          0
        );

        // Update tooltip text
        tooltip.textContent = `${totalItems} ítems - ${totalPrice.toFixed(2)}€`;
      });
    }
  });
}

/**
 * Add product to cart
 */

function addToCart(name, price, id, stock) {
  // Get existing cart or initialize empty array
  const cart = JSON.parse(localStorage.getItem("cart")) || [];

  // Check if product already in cart
  const existingItem = cart.find((item) => item.id === id);

  if (existingItem) {
    // Don't exceed available stock
    if (existingItem.quantity < stock) {
      existingItem.quantity += 1;
    } else {
      alert("No hi ha més estoc disponible d'aquest producte");
      return;
    }
  } else {
    // Add new item
    cart.push({
      id: id,
      name: name,
      price: price,
      quantity: 1,
      stock: stock,
    });
  }

  // Save updated cart
  localStorage.setItem("cart", JSON.stringify(cart));

  // Update cart count
  updateCartCount();

  // Show success message
  alert(`${name} afegit al carret!`);
}

// Initialize cart when DOM is loaded

document.addEventListener("DOMContentLoaded", () => {
  initializeCart();

  // Sincroniza el carrito JS con el backend al enviar el checkout
  const checkoutForm = document.querySelector('form[action*="process-order"]');
  if (checkoutForm) {
    checkoutForm.addEventListener("submit", function () {
      document.getElementById("cart_json").value =
        localStorage.getItem("cart") || "[]";
    });
  }
});
