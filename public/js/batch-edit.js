/**
 * Batch Editing Functionality for Products
 */
document.addEventListener("DOMContentLoaded", () => {
  // Elements
  const selectAllCheckbox = document.getElementById("select-all");
  const productCheckboxes = document.querySelectorAll(".product-checkbox");
  const updateSelectedButton = document.getElementById("update-selected");
  const selectedCountSpan = document.getElementById("selected-count");
  const quickEditButtons = document.querySelectorAll(".quick-edit-btn");
  const quickEditModal = document.getElementById("quick-edit-modal");
  const quickEditForm = document.getElementById("quick-edit-form");
  const closeModalButton = document.getElementById("close-modal");
  const searchForm = document.getElementById("search-form");
  const sortLinks = document.querySelectorAll(".sort-link");

  // Define BASE_URL (or retrieve it from a data attribute, etc.)
  const BASE_URL = document
    .querySelector('meta[name="base-url"]')
    .getAttribute("content");

  // Function to update the selected count and button state
  function updateSelectedCount() {
    const checkedCount = document.querySelectorAll(
      ".product-checkbox:checked"
    ).length;
    selectedCountSpan.textContent = `${checkedCount} producte${
      checkedCount !== 1 ? "s" : ""
    } seleccionat${checkedCount !== 1 ? "s" : ""}`;
    updateSelectedButton.disabled = checkedCount === 0;
  }

  // Select all checkbox
  if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener("change", function () {
      productCheckboxes.forEach((checkbox) => {
        checkbox.checked = this.checked;
      });
      updateSelectedCount();
    });
  }

  // Individual checkboxes
  productCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      // If any checkbox is unchecked, uncheck the "select all" checkbox
      if (!this.checked && selectAllCheckbox) {
        selectAllCheckbox.checked = false;
      }
      // If all checkboxes are checked, check the "select all" checkbox
      else if (
        selectAllCheckbox &&
        document.querySelectorAll(".product-checkbox:not(:checked)").length ===
          0
      ) {
        selectAllCheckbox.checked = true;
      }

      updateSelectedCount();
    });
  });

  // Quick edit functionality
  quickEditButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const productId = this.getAttribute("data-id");

      // Show loading state
      this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

      // Fetch product details
      fetch(`${BASE_URL}/productes/get-product-details.php?id=${productId}`)
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Reset button
            this.innerHTML = '<i class="fas fa-edit"></i>';

            // Fill the form with product details
            const product = data.product;
            document.getElementById("quick-edit-id").value = product.id;
            document.getElementById("quick-edit-nom").value = product.nom;
            document.getElementById("quick-edit-descripcio").value =
              product.descripcio;
            document.getElementById("quick-edit-preu").value = product.preu;
            document.getElementById("quick-edit-estoc").value = product.estoc;

            const categoriaSelect = document.getElementById(
              "quick-edit-categoria"
            );
            if (categoriaSelect) {
              for (let i = 0; i < categoriaSelect.options.length; i++) {
                if (categoriaSelect.options[i].value === product.categoria) {
                  categoriaSelect.selectedIndex = i;
                  break;
                }
              }
            }

            // Show the modal
            quickEditModal.style.display = "block";

            // Set up preview updates
            setupPreviewUpdates(product.id);
          } else {
            alert(data.message || "Error al obtenir els detalls del producte");
            this.innerHTML = '<i class="fas fa-edit"></i>';
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Error al obtenir els detalls del producte");
          this.innerHTML = '<i class="fas fa-edit"></i>';
        });
    });
  });

  // Close modal
  if (closeModalButton) {
    closeModalButton.addEventListener("click", () => {
      quickEditModal.style.display = "none";
    });
  }

  // Close modal when clicking outside
  window.addEventListener("click", (event) => {
    if (event.target === quickEditModal) {
      quickEditModal.style.display = "none";
    }
  });

  // Quick edit form submission
  if (quickEditForm) {
    quickEditForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const submitButton = this.querySelector('button[type="submit"]');

      // Show loading state
      submitButton.innerHTML =
        '<i class="fas fa-spinner fa-spin"></i> Guardant...';
      submitButton.disabled = true;

      // Send AJAX request
      fetch(`${BASE_URL}/productes/quick-update.php`, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Update the product in the table
            updateProductInTable(data.product);

            // Close the modal
            quickEditModal.style.display = "none";

            // Show success message
            showMessage("success", data.message);
          } else {
            showMessage(
              "danger",
              data.message || "Error al actualitzar el producte"
            );
          }

          // Reset button
          submitButton.innerHTML = "Guardar canvis";
          submitButton.disabled = false;
        })
        .catch((error) => {
          console.error("Error:", error);
          showMessage("danger", "Error al actualitzar el producte");

          // Reset button
          submitButton.innerHTML = "Guardar canvis";
          submitButton.disabled = false;
        });
    });
  }

  // Setup real-time preview updates
  function setupPreviewUpdates(productId) {
    const nameInput = document.getElementById("quick-edit-nom");
    const descInput = document.getElementById("quick-edit-descripcio");
    const priceInput = document.getElementById("quick-edit-preu");
    const stockInput = document.getElementById("quick-edit-estoc");

    // Get the product row
    const productRow = document.querySelector(`tr[data-id="${productId}"]`);

    if (productRow) {
      // Update name
      if (nameInput) {
        nameInput.addEventListener("input", function () {
          const nameCell = productRow.querySelector("td.product-name");
          if (nameCell) {
            nameCell.textContent = this.value;
          }
        });
      }

      // Update price
      if (priceInput) {
        priceInput.addEventListener("input", function () {
          const priceCell = productRow.querySelector("td.product-price");
          if (priceCell) {
            priceCell.textContent =
              Number.parseFloat(this.value).toFixed(2) + " €";
          }
        });
      }

      // Update stock
      if (stockInput) {
        stockInput.addEventListener("input", function () {
          const stockBadge = productRow.querySelector("td .stock-badge");
          if (stockBadge) {
            const stockValue = Number.parseInt(this.value);
            stockBadge.textContent = stockValue;

            // Update badge color
            stockBadge.className =
              "badge " +
              (stockValue > 5 ? "bg-success" : "bg-warning") +
              " stock-badge";
          }
        });
      }
    }
  }

  // Update product in the table
  function updateProductInTable(product) {
    const productRow = document.querySelector(`tr[data-id="${product.id}"]`);

    if (productRow) {
      // Update name
      const nameCell = productRow.querySelector("td.product-name");
      if (nameCell) {
        nameCell.textContent = product.nom;
      }

      // Update category
      const categoryCell = productRow.querySelector("td.product-category");
      if (categoryCell) {
        categoryCell.textContent = product.categoria || "Sense categoria";
      }

      // Update price
      const priceCell = productRow.querySelector("td.product-price");
      if (priceCell) {
        priceCell.textContent =
          Number.parseFloat(product.preu).toFixed(2) + " €";
      }

      // Update stock
      const stockBadge = productRow.querySelector("td .stock-badge");
      if (stockBadge) {
        stockBadge.textContent = product.estoc;
        stockBadge.className =
          "badge " +
          (product.estoc > 5 ? "bg-success" : "bg-warning") +
          " stock-badge";
      }
    }
  }

  // Show message
  function showMessage(type, message) {
    const alertContainer = document.getElementById("alert-container");

    if (alertContainer) {
      const alert = document.createElement("div");
      alert.className = `alert alert-${type} alert-dismissible fade show`;
      alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

      alertContainer.appendChild(alert);

      // Auto-dismiss after 5 seconds
      setTimeout(() => {
        alert.classList.remove("show");
        setTimeout(() => {
          alertContainer.removeChild(alert);
        }, 150);
      }, 5000);
    }
  }

  // Initialize
  updateSelectedCount();
});
