/**
 * Main JavaScript file
 * Initializes all functionality and provides global access to modules
 */

// Define auth variables with default values
// These should be set by the server in the actual page
var isLoggedIn = false;
var isAdmin = false;

/**
 * Initialize the application when the DOM is fully loaded
 */
document.addEventListener("DOMContentLoaded", () => {
  console.log("Jardineria application initialized");

  // Initialize first product list as visible by default
  const productLists = document.querySelectorAll(".product-list");
  productLists.forEach((list, index) => {
    list.style.display = index === 0 ? "flex" : "none";
  });

  // Set up event delegation for add to cart buttons
  document.body.addEventListener("click", (event) => {
    // Handle add to cart button clicks
    if (event.target.classList.contains("add-to-cart")) {
      const productName = event.target.getAttribute("data-name");
      const productPrice = Number.parseFloat(
        event.target.getAttribute("data-price")
      );

      if (productName && !isNaN(productPrice)) {
        window.cartModule.addToCart(productName, productPrice);
      }
    }
  });
});

// Update any hardcoded paths to use the BASE_URL JavaScript variable
document.addEventListener("DOMContentLoaded", () => {
  // Ensure BASE_URL is available in JavaScript
  if (typeof BASE_URL === "undefined") {
    console.warn(
      "BASE_URL is not defined. Some functionality may not work correctly."
    );
  }

  // Initialize any components that need the base URL
  initializeComponents();
});

function initializeComponents() {
  // Any initialization code that needs BASE_URL
}

/**
 * Set authentication status - to be called from PHP
 * @param {boolean} loggedIn - Whether user is logged in
 * @param {boolean} admin - Whether user is an admin
 */
function setAuthStatus(loggedIn, admin) {
  isLoggedIn = loggedIn;
  isAdmin = admin;

  // Update UI based on new auth status
  if (window.uiModule) {
    window.uiModule.setupAuthUI(isLoggedIn, isAdmin);
  }
}
