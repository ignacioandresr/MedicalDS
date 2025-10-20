// Inicializar dropdowns de Bootstrap y fallback manual para el dropdown del usuario
document.addEventListener('DOMContentLoaded', function () {
    try {
        var dropdownTriggerList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        dropdownTriggerList.map(function (dropdownTriggerEl) {
            return new bootstrap.Dropdown(dropdownTriggerEl);
        });
    } catch (e) {
        console && console.error && console.error('Bootstrap dropdown init error:', e);
    }

    // Fallback manual toggle para el dropdown del usuario si Bootstrap no inicializa correctamente
    var trigger = document.getElementById('navbarDropdown');
    if (!trigger) return;
    var menu = trigger.nextElementSibling; // asumimos que el menu es el siguiente sibling
    if (!menu) return;

    // Asegurarnos que el menu tiene la clase dropdown-menu
    if (!menu.classList.contains('dropdown-menu')) return;

    trigger.addEventListener('click', function (e) {
        e.preventDefault();
        // toggle clase show tanto en el trigger como en el menu
        trigger.classList.toggle('show');
        menu.classList.toggle('show');
        // manejar aria-expanded
        var expanded = trigger.getAttribute('aria-expanded') === 'true';
        trigger.setAttribute('aria-expanded', (!expanded).toString());
    });

    // cerrar al hacer click fuera
    document.addEventListener('click', function (e) {
        if (!trigger.contains(e.target) && !menu.contains(e.target)) {
            if (menu.classList.contains('show')) {
                trigger.classList.remove('show');
                menu.classList.remove('show');
                trigger.setAttribute('aria-expanded', 'false');
            }
        }
    });
});
