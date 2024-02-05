const dropdownButton = document.getElementById("user-menu-button");
const dropdownMenu = document.querySelector('[role="menu"]');

dropdownButton.addEventListener("click", () => {
  dropdownMenu.classList.toggle("hidden");
});

const menuButton = document.querySelector('[aria-controls="mobile-menu"]');
const menu = document.getElementById("mobile-menu");

menuButton.addEventListener("click", () => {
  menu.classList.toggle("hidden");
});