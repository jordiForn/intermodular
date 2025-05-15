/**
 * UI Management Module
 * Handles UI interactions like toggling visibility, search functionality,
 * and other user interface elements.
 */

/**
 * Safely checks if an element exists before performing operations
 * @param {string} selector - CSS selector for the element
 * @param {Function} callback - Function to execute if element exists
 */
function withElement(selector, callback) {
  const element = document.querySelector(selector);
  if (element) {
    callback(element);
  }
}

/**
 * Toggles visibility of admin menu
 */
function toggleAdminMenu() {
  withElement("#admin-menu", (menu) => {
    menu.classList.toggle("visible");
  });
}

/**
 * Toggles visibility of search input
 */
function toggleSearchInput() {
  withElement("#search-input", (input) => {
    input.style.display = input.style.display === "none" ? "block" : "none";
  });
}

/**
 * Toggles visibility of product lists
 * @param {HTMLElement} button - The toggle button that was clicked
 */
function toggleProductList(button) {
  const productList = button.nextElementSibling;
  if (productList) {
    productList.style.display =
      productList.style.display === "flex" ? "none" : "flex";
  }
}

/**
 * Initializes search functionality for product cards
 */
function initializeSearch() {
  withElement("#search-input", (searchInput) => {
    const productCards = document.querySelectorAll(".product-card");

    searchInput.addEventListener("input", () => {
      const searchTerm = searchInput.value.toLowerCase();

      productCards.forEach((card) => {
        const productName = card.querySelector("h3").innerText.toLowerCase();
        card.style.display = productName.includes(searchTerm)
          ? "block"
          : "none";
      });
    });
  });
}

/**
 * Sets up UI based on user authentication status
 * @param {boolean} isLoggedIn - Whether user is logged in
 * @param {boolean} isAdmin - Whether user is an admin
 */
function setupAuthUI(isLoggedIn, isAdmin) {
  if (isLoggedIn) {
    withElement("#logout-link", (link) => {
      link.style.display = "inline-block";
    });

    if (isAdmin) {
      withElement("#menu-icon", (icon) => {
        icon.style.display = "inline-block";
      });

      withElement("#contact-icon", (icon) => {
        icon.style.display = "none";
      });
    }
  }
}

/**
 * Sets up contact form fields based on authentication status
 * @param {boolean} isLoggedIn - Whether user is logged in
 */
function setupContactForm(isLoggedIn) {
  if (!isLoggedIn) {
    [
      "name-label",
      "name",
      "email-label",
      "email",
      "phone-label",
      "phone",
    ].forEach((id) => {
      withElement(`#${id}`, (element) => {
        element.style.display = "inline-block";
      });
    });
  }
}

// Initialize UI components when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  // Set up toggle buttons for product lists
  document.querySelectorAll(".toggle-button").forEach((button) => {
    button.addEventListener("click", () => toggleProductList(button));
  });

  // Initialize search functionality
  initializeSearch();

  // Set up page-specific functionality
  const path = window.location.pathname;

  if (path.includes("index.php")) {
    withElement("#menu-icon", (menuIcon) => {
      menuIcon.addEventListener("click", toggleAdminMenu);
    });

    withElement("#search-icon", (searchIcon) => {
      searchIcon.addEventListener("click", toggleSearchInput);
    });

    // Check if auth variables are defined
    if (typeof isLoggedIn !== "undefined" && typeof isAdmin !== "undefined") {
      setupAuthUI(isLoggedIn, isAdmin);
    }
  }

  if (path.includes("contact.php")) {
    // Check if auth variable is defined
    if (typeof isLoggedIn !== "undefined") {
      setupContactForm(isLoggedIn);
    }
  }
});

// Export functions for use in other modules
window.uiModule = {
  toggleAdminMenu,
  toggleSearchInput,
  toggleProductList,
  initializeSearch,
};
