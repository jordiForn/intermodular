/**
 * Cart Management Module
 * Handles all cart-related functionality including adding/removing items,
 * updating the cart display, and calculating totals.
 */

// Initialize cart from localStorage or create empty cart
let cart = JSON.parse(localStorage.getItem("cart")) || [];

/**
 * Updates the cart tooltip to show current items and total
 */
function updateTooltip() {
  let totalItems = 0;
  let totalPrice = 0;

  cart.forEach((item) => {
    totalItems += item.quantity;
    totalPrice += item.price * item.quantity;
  });

  const formattedPrice = totalPrice.toFixed(2).replace(".", ",");

  const tooltips = document.querySelectorAll(".tooltip-text");
  tooltips.forEach((tooltip) => {
    tooltip.innerText = `${totalItems} ítems - ${formattedPrice}€`;
  });
}

/**
 * Adds a product to the cart
 * @param {string} name - Product name
 * @param {number} price - Product price
 * @param {number} id - Product ID
 * @param {number} stock - Product stock
 */
function addToCart(name, price, id, stock) {
  try {
    const existingItem = cart.find((item) => item.id === id);

    if (existingItem) {
      // Check if adding more would exceed stock
      if (existingItem.quantity < stock) {
        existingItem.quantity += 1;
      } else {
        alert(`No hi ha més estoc disponible de ${name}.`);
        return;
      }
    } else {
      cart.push({ id, name, price, quantity: 1 });
    }

    localStorage.setItem("cart", JSON.stringify(cart));

    updateTooltip();
    alert(name + " s'ha afegit al carret.");
  } catch (error) {
    console.error("Error adding item to cart:", error);
  }
}

/**
 * Removes an item from the cart
 * @param {number} index - Index of the item to remove
 */
function removeFromCart(index) {
  try {
    index = Number.parseInt(index);
    if (cart[index].quantity > 1) {
      cart[index].quantity -= 1;
    } else {
      cart.splice(index, 1);
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    loadCart();
    updateTooltip();
  } catch (error) {
    console.error("Error removing item from cart:", error);
  }
}

/**
 * Loads and displays the cart contents
 */
function loadCart() {
  try {
    const cartContainer = document.getElementById("cart-items");
    const totalElement = document.getElementById("cart-total");

    if (!cartContainer || !totalElement) {
      return; // Not on cart page
    }

    cart = JSON.parse(localStorage.getItem("cart")) || [];
    cartContainer.innerHTML = "";
    let total = 0;

    cart.forEach((item, index) => {
      total += item.price * item.quantity;
      const itemElement = document.createElement("div");
      itemElement.classList.add("cart-item");
      itemElement.innerHTML = `
        <span>${item.name} (x${item.quantity})</span>
        <span>${(item.price * item.quantity).toFixed(2)}€</span>
        <button class="remove-btn" data-index="${index}">X</button>
      `;
      cartContainer.appendChild(itemElement);
    });

    totalElement.innerText = total.toFixed(2) + "€";
    setupCartListeners();
  } catch (error) {
    console.error("Error loading cart:", error);
  }
}

/**
 * Sets up event listeners for cart item removal
 */
function setupCartListeners() {
  document.querySelectorAll(".remove-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const index = this.getAttribute("data-index");
      removeFromCart(index);
    });
  });
}

/**
 * Updates the hidden total input for form submission
 */
function getTotal() {
  const hiddenTotalInput = document.getElementById("cart-total-hidden");
  if (!hiddenTotalInput) return;

  let total = 0;
  cart.forEach((item) => {
    total += item.price * item.quantity;
  });
  hiddenTotalInput.value = total.toFixed(2);
}

// Initialize tooltip on page load
document.addEventListener("DOMContentLoaded", () => {
  updateTooltip();

  // Load cart if on cart page
  if (
    window.location.pathname.includes("cart.php") ||
    window.location.pathname.includes("cart.html")
  ) {
    loadCart();
  }
});

// Export functions for use in other modules
window.cartModule = {
  addToCart,
  removeFromCart,
  loadCart,
  getTotal,
  updateTooltip,
};
