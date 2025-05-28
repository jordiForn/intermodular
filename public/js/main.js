/**
 * Initialize cart functionality
 */
console.log("Main.js cargado");
function initializeCart() {
  // Update cart count on page load
  updateCartCount();

  // Initialize tooltips for cart buttons
  initializeTooltips();

  // Add event listeners to cart buttons
  const addToCartButtons = document.querySelectorAll(
    ".tooltip-container button"
  );
  addToCartButtons.forEach((button) => {
    console.log("Añadiendo listener");
    button.addEventListener("click", function (e) {
      e.preventDefault();
      console.log("Botón de añadir al carrito clicado");
      const name = this.getAttribute("data-name");
      const price = Number.parseFloat(this.getAttribute("data-price"));
      const id = Number.parseInt(this.getAttribute("data-id"));
      const stock = Number.parseInt(this.getAttribute("data-stock"));

      addToCart(name, price, id, stock);
    });
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
  console.log("addToCart llamado");
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
  showCartMessage(`${name} afegit al carret!`);
}

// Initialize cart when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  initializeCart();
});

function showCartMessage(message) {
  let msgDiv = document.getElementById("cart-message");
  if (!msgDiv) {
    msgDiv = document.createElement("div");
    msgDiv.id = "cart-message";
    msgDiv.style.position = "fixed";
    msgDiv.style.top = "20px";
    msgDiv.style.right = "20px";
    msgDiv.style.zIndex = "9999";
    msgDiv.style.background = "#28a745";
    msgDiv.style.color = "white";
    msgDiv.style.padding = "12px 24px";
    msgDiv.style.borderRadius = "8px";
    msgDiv.style.boxShadow = "0 2px 8px rgba(0,0,0,0.2)";
    document.body.appendChild(msgDiv);
  }
  msgDiv.textContent = message;
  msgDiv.style.display = "block";
  setTimeout(() => {
    msgDiv.style.display = "none";
  }, 2000);
}
