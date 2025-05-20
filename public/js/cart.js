// Function to add items to cart
function addToCart(name, price, id, stock) {
  event.preventDefault();

  // Get current cart from localStorage
  const cart = JSON.parse(localStorage.getItem("cart")) || [];

  // Check if item already exists in cart
  const existingItemIndex = cart.findIndex((item) => item.id === id);

  if (existingItemIndex !== -1) {
    // Item exists, increase quantity if stock allows
    if (cart[existingItemIndex].quantity < stock) {
      cart[existingItemIndex].quantity += 1;
    } else {
      alert("No hi ha més estoc disponible per aquest producte.");
      return;
    }
  } else {
    // Item doesn't exist, add new item
    cart.push({
      id: id,
      name: name,
      price: price,
      quantity: 1,
      stock: stock,
    });
  }

  // Save updated cart to localStorage
  localStorage.setItem("cart", JSON.stringify(cart));

  // Update cart count in UI
  updateCartCount();

  // Dispatch event for other components to listen
  window.dispatchEvent(new Event("cartUpdated"));

  // Show confirmation
  alert(`${name} afegit al carret!`);
}

// Function to update cart count in UI
function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

  // Update any cart count elements
  const cartCountElements = document.querySelectorAll(".cart-count");
  cartCountElements.forEach((element) => {
    element.textContent = totalItems;
  });
}

// Initialize cart count on page load
document.addEventListener("DOMContentLoaded", () => {
  updateCartCount();
});
