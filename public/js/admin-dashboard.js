import { Chart } from "@/components/ui/chart";
// Admin Dashboard JavaScript

document.addEventListener("DOMContentLoaded", () => {
  // Initialize sales chart
  initSalesChart();

  // Add event listeners for dashboard actions
  document.getElementById("viewAllOrders").addEventListener("click", () => {
    // This would typically navigate to an orders page
    alert("Aquesta funcionalitat s'implementarà properament.");
  });

  document.getElementById("exportData").addEventListener("click", () => {
    alert("Exportació de dades en desenvolupament.");
  });

  document.getElementById("systemSettings").addEventListener("click", () => {
    alert("Configuració del sistema en desenvolupament.");
  });

  // Add tooltips to all elements with data-bs-toggle="tooltip"
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );
});

// Initialize the sales chart with dummy data
// In a real application, this data would come from the server
function initSalesChart() {
  const ctx = document.getElementById("salesChart");

  if (!ctx) return;

  // Sample data - in a real app, this would be populated from the backend
  const months = ["Gen", "Feb", "Mar", "Abr", "Mai", "Jun"];
  const salesData = [1200, 1900, 1500, 2500, 2100, 3000];

  new Chart(ctx, {
    type: "line",
    data: {
      labels: months,
      datasets: [
        {
          label: "Vendes (€)",
          data: salesData,
          backgroundColor: "rgba(77, 170, 87, 0.2)",
          borderColor: "#4daa57",
          borderWidth: 2,
          tension: 0.3,
          pointBackgroundColor: "#754668",
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: (value) => value + " €",
          },
        },
      },
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          callbacks: {
            label: (context) => context.parsed.y + " €",
          },
        },
      },
    },
  });
}

// Function to handle product stock updates
const BASE_URL = ""; // Define BASE_URL or get it from a configuration
function updateProductStock(productId, newStock) {
  // This would typically make an AJAX request to update the stock
  console.log(`Updating product ${productId} stock to ${newStock}`);

  fetch(`${BASE_URL}/productes/quick-update.php`, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `id=${productId}&stock=${newStock}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Stock actualitzat correctament");
      } else {
        alert("Error en actualitzar el stock");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error en la comunicació amb el servidor");
    });
}
