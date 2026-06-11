document.getElementById('formEnviarCodigo').addEventListener('submit', function(e) {
    // 1. Evitamos que la página se recargue
    e.preventDefault(); 
    
    // Aquí es donde en el futuro pondrás tu código AJAX para decirle a CodeIgniter 
    // que envíe el correo real. Por ahora, hace el cambio visual directo:

    // 2. Ocultamos el primer contenedor
    document.getElementById('pasoEnviarCodigo').classList.add('oculto');
    
    // 3. Mostramos el segundo contenedor quitando la clase 'oculto'
    document.getElementById('pasoVerificarCodigo').classList.remove('oculto');
});