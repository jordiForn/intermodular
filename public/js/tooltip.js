/**
 * Enhanced Tooltip Module for Add to Cart Buttons
 * Provides informative tooltips when hovering over Add to Cart buttons
 */

document.addEventListener("DOMContentLoaded", () => {
  // Create a single tooltip element to be reused
  const tooltip = document.createElement("div");
  tooltip.className = "product-tooltip";
  tooltip.style.display = "none";
  document.body.appendChild(tooltip);

  // Track if the mouse is over the button or the tooltip
  let isOverButton = false;
  let isOverTooltip = false;

  // Allow hovering over the tooltip itself without it disappearing
  tooltip.addEventListener("mouseenter", () => {
    isOverTooltip = true;
  });

  tooltip.addEventListener("mouseleave", () => {
    isOverTooltip = false;
    if (!isOverButton) {
      hideTooltip();
    }
  });

  // Use event delegation for better performance
  document.body.addEventListener(
    "mouseenter",
    (event) => {
      // Check if the hovered element is an Add to Cart button or its parent
      const button = event.target.closest(".add-to-cart");
      if (!button) return;

      isOverButton = true;

      // Get product information from data attributes
      const productName = button.getAttribute("data-name");
      const productPrice = button.getAttribute("data-price");
      const productStock = button.getAttribute("data-stock") || "In stock";
      const productImage = button.getAttribute("data-image");

      // Create tooltip content
      tooltip.innerHTML = `
      <div class="tooltip-header">
        <h4>${productName}</h4>
        <span class="tooltip-price">${productPrice}€</span>
      </div>
      <div class="tooltip-body">
        <div class="tooltip-stock">${productStock}</div>
        <div class="tooltip-message">Click to add to your cart</div>
      </div>
    `;

      // Position the tooltip above the button
      positionTooltip(button, tooltip);

      // Show the tooltip with a fade-in effect
      tooltip.style.display = "block";
      setTimeout(() => {
        tooltip.classList.add("visible");
      }, 10);
    },
    true
  );

  document.body.addEventListener(
    "mouseleave",
    (event) => {
      const button = event.target.closest(".add-to-cart");
      if (!button) return;

      isOverButton = false;

      // Hide tooltip if not hovering over the tooltip itself
      if (!isOverTooltip) {
        hideTooltip();
      }
    },
    true
  );

  // Hide tooltip when clicking anywhere
  document.addEventListener("click", hideTooltip);

  // Hide tooltip when scrolling
  window.addEventListener("scroll", hideTooltip);

  /**
   * Positions the tooltip above the target element
   * @param {HTMLElement} target - The element to position the tooltip above
   * @param {HTMLElement} tooltip - The tooltip element
   */
  function positionTooltip(target, tooltip) {
    const rect = target.getBoundingClientRect();
    const tooltipHeight = tooltip.offsetHeight;
    const tooltipWidth = tooltip.offsetWidth;

    // Position above the button with a small gap
    let top = rect.top - tooltipHeight - 10;
    let left = rect.left + rect.width / 2 - tooltipWidth / 2;

    // Ensure tooltip stays within viewport
    if (top < 10) {
      // Position below if not enough space above
      top = rect.bottom + 10;
      tooltip.classList.add("bottom");
    } else {
      tooltip.classList.remove("bottom");
    }

    // Prevent tooltip from going off-screen horizontally
    if (left < 10) {
      left = 10;
    } else if (left + tooltipWidth > window.innerWidth - 10) {
      left = window.innerWidth - tooltipWidth - 10;
    }

    tooltip.style.top = `${top}px`;
    tooltip.style.left = `${left}px`;
  }

  /**
   * Hides the tooltip with a fade-out effect
   */
  function hideTooltip() {
    tooltip.classList.remove("visible");
    setTimeout(() => {
      tooltip.style.display = "none";
    }, 200); // Match transition duration in CSS
  }
});
