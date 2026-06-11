<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');



//SECCION PARA TODO LA PARTE DE AUTETIFICAR USUARIO
$routes->group('autentificar', static function($routes) {
    $routes->get('registro',  'Autentificar\Registrar::registro');
    $routes->post('registro', 'Autentificar\Registrar::procesarRegistro'); 
    $routes->get('ingreso',   'Autentificar\Ingresar::ingreso');
       $routes->post('ingreso',  'Autentificar\Ingresar::procesarIngreso'); 
    $routes->get('recuperar', 'Autentificar\Recuperar::restablecer');
    $routes->get('rol',       'Autentificar\Registrar::rol_p');
    $routes->post('rol',      'Autentificar\Registrar::procesarRol');    
});



//Rutas carpeta Administrador
$routes->group('administrador', static function($routes) {
    $routes->get( 'panel','Administrador\PanelAdmin::dashboard');
    $routes->post('perfil/actualizar', 'Administrador\PanelAdmin::actualizarPerfil'); // ← nueva
    $routes->get('vehiculo','Administrador\AdminVehiculo::vehiculo');
    $routes->get('taller','Administrador\AdminTaller::taller');
    $routes->get('calificacion','Administrador\AdminCalificacion::calificacion');
    $routes->get('reporte','Administrador\AdminReporte::Reporte');
    $routes->get('salir','Autentificar\Ingresar::salir');
});

//RUTAS CARPETA PROPIETARIO
$routes->group('propietario', static function($routes) {
    $routes->get( 'panel','Propietario\PanelPro::dashboard');
    $routes->post('perfil/actualizar', 'Propietario\PanelPro::actualizarPerfil'); // ← nueva
    $routes->get('notificacion','Propietario\ProNotificacion::notificacion');
    $routes->get('vehiculo','Propietario\ProVehiculo::vehiculo');
    $routes->get('taller','Propietario\ProTaller::taller');
    $routes->post('taller/calificar', 'Propietario\ProTaller::calificar');
    $routes->get('cita','Propietario\ProCita::cita');
    $routes->get('calificacion','Propietario\ProCalificacion::calificacion');
    $routes->get('salir','Autentificar\Ingresar::salir');
});

// RUTAS CARPETA MECANICO
$routes->group('mecanico', static function($routes) {
    $routes->get( 'panel',                  'Mecanico\PanelMecanico::dashboard');
    $routes->post('perfil/actualizar',      'Mecanico\PanelMecanico::actualizarPerfil');
    $routes->get( 'taller',                 'Mecanico\MecanicoTaller::taller');
    $routes->post('taller/registrar',       'Mecanico\MecanicoTaller::registrar');
    $routes->post('taller/desactivar',      'Mecanico\MecanicoTaller::desactivar'); // ← NUEVA
    $routes->post('taller/activar',         'Mecanico\MecanicoTaller::activar'); 
    $routes->post('taller/actualizar',         'Mecanico\MecanicoTaller::actualizar'); 
    $routes->get( 'cita',                   'Mecanico\MecanicoCita::cita');
    $routes->get( 'control',               'Mecanico\MecanicoControl::control');
    $routes->get( 'calificacion',           'Mecanico\MecanicoCalificacion::calificacion');
    $routes->get( 'salir',                  'Autentificar\Ingresar::salir');
});


