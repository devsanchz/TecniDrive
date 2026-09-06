<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');



//SECCION PARA TODO LA PARTE DE AUTETIFICAR USUARIO
$routes->group('autentificar', static function($routes) {
    $routes->get('registro', 'Autentificar\Registrar::registro');
    $routes->get('ingreso', 'Autentificar\Ingresar::ingreso');
    $routes->get('recuperar', 'Autentificar\Recuperar::restablecer');
    $routes->get('rol', 'Autentificar\Registrar::rol_p');
});



//Rutas carpeta Administrador
$routes->group('administrador',static function($routes){
    $routes->get('panel','Administrador\PanelAdmin::dashboard');
    $routes->get('vehiculo','Administrador\AdminVehiculo::vehiculo');
    $routes->get('taller','Administrador\AdminTaller::taller');
    $routes->get('calificacion','Administrador\AdminCalificacion::calificacion');
    $routes->get('reporte','Administrador\AdminReporte::Reporte');
});



//RUTAS CARPETA PROPIETARIO
$routes->group('propietario',static function($routes){
    $routes->get('panel','Propietario\PanelPro::dashboard');
    $routes->get('notificacion','Propietario\ProNotificacion::notificacion');
    $routes->get('vehiculo','Propietario\ProVehiculo::vehiculo');
    $routes->get('taller','Propietario\ProTaller::taller');
    $routes->get('cita','Propietario\ProCita::cita');
    $routes->get('calificacion','Propietario\ProCalificacion::calificacion');
  
});


//RUTAS CARPETA MECANICO
$routes->group('mecanico',static function($routes){
    $routes->get('panel','Mecanico\PanelMecanico::dashboard');
    $routes->get('taller','Mecanico\MecanicoTaller::taller');
    $routes->get('cita','Mecanico\MecanicoCita::cita');
    $routes->get('control','Mecanico\MecanicoControl::control');
    $routes->get('calificacion','Mecanico\MecanicoCalificacion::calificacion');
});


