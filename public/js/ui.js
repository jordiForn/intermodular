// Initialize toggle buttons for product categories
document.addEventListener("DOMContentLoaded", () => {
  const toggleButtons = document.querySelectorAll(".toggle-button");
  toggleButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const list = this.nextElementSibling;
      list.classList.toggle("active");
    });
  });

  // If we're on the homepage, activate the first category by default
  if (
    window.location.pathname === "/" ||
    window.location.pathname.includes("index.php")
  ) {
    const firstToggleButton = document.querySelector(".toggle-button");
    if (firstToggleButton) {
      const firstList = firstToggleButton.nextElementSibling;
      if (firstList) {
        firstList.classList.add("active");
      }
    }
  }
});
