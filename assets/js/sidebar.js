const sidebarMini = document.getElementById('sidebar-mini');
const navbarMini = document.getElementById('navbar-mini');
const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');

toggleSidebarBtn.addEventListener('click', function() {
    sidebarMini.classList.toggle('translate-x-0');
    sidebarMini.classList.toggle('-translate-x-full');
});