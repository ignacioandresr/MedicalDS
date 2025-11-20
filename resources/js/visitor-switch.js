document.addEventListener('DOMContentLoaded', function () {
    // Buscar ambos switches (escritorio y móvil)
    const switchDesktop = document.getElementById('martian-interface-switch');
    const switchMobile = document.getElementById('martian-interface-switch-mobile');
    
    // Función para manejar el cambio de switch
    function handleSwitchChange(e) {
        console.log('Checkbox cambió, nuevo estado:', e.target.checked);
        
        // Sincronizar ambos switches
        if (switchDesktop) switchDesktop.checked = e.target.checked;
        if (switchMobile) switchMobile.checked = e.target.checked;
        
        if (e.target.checked) {
            console.log('Activando interfaz marciana (directo a welcome_ru)...');
            window.location.href = '/visitor/welcome_ru';
            return;
        }
        
        console.log('Desactivando interfaz marciana (POST /visitor/leave)...');
        fetch('/visitor/leave', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({})
        }).then(function(resp){
            window.location.href = '/';
        }).catch(function(err){
            console.warn('Fallo al salir visitante', err);
            window.location.href = '/';
        });
    }
    
    // Agregar event listeners a ambos switches si existen
    if (switchDesktop) {
        console.log('Switch escritorio encontrado, estado inicial:', switchDesktop.checked);
        switchDesktop.addEventListener('change', handleSwitchChange);
    }
    
    if (switchMobile) {
        console.log('Switch móvil encontrado, estado inicial:', switchMobile.checked);
        switchMobile.addEventListener('change', handleSwitchChange);
    }
    
    if (!switchDesktop && !switchMobile) {
        console.log('No se encontró ningún checkbox martian-interface-switch');
    }
});
