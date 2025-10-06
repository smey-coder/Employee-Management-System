const menu = document.getElementById('Menu');
const sidebar = document.getElementById('Sidebar');
const main = document.getElementById('main');

menu.addEventListener('click', () => {
    sidebar.classList.toggle('menu-toggle');
    menu.classList.toggle('menu-toggle');
    main.classList.toggle('menu-toggle');
});