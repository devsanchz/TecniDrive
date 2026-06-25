<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');





// RUTAS PARTE AUTENTIFICAR USUARIO
$routes->group('autentificar', static function($routes) {

    $routes->get ('registro',          'Autentificar\Registrar::registro');

    $routes->post('registro',          'Autentificar\Registrar::procesarRegistro');

    $routes->get ('ingreso',           'Autentificar\Ingresar::ingreso');

    $routes->post('ingreso',           'Autentificar\Ingresar::procesarIngreso');
//PARTE de la recuperacion de contraseña
    $routes->get ('recuperar',         'Autentificar\Recuperar::restablecer');

    $routes->post('enviar-codigo',     'Autentificar\Recuperar::enviarCodigo');

    $routes->get ('verificar-codigo',  'Autentificar\Recuperar::mostrarVerificar');

    $routes->post('verificar-codigo',  'Autentificar\Recuperar::verificarCodigo');

    $routes->get ('nuevo-pass',        'Autentificar\Recuperar::nuevoPass');

    $routes->post('actualizar-pass',   'Autentificar\Recuperar::actualizarPass');
//
    $routes->get ('rol',               'Autentificar\Registrar::rol_p');

    $routes->post('rol',               'Autentificar\Registrar::procesarRol');
});



//Rutas carpeta Administrador
$routes->group('administrador', ['filter' => 'auth:1'], static function($routes) {
    $routes->get( 'panel',                     'Administrador\PanelAdmin::dashboard');

    $routes->get('vehiculo',                   'Administrador\AdminVehiculo::vehiculo');

    $routes->get('taller',                     'Administrador\AdminTaller::taller');

    $routes->post('administrador/taller/activar',    'Administrador\AdminTaller::activar');

    $routes->post('administrador/taller/desactivar', 'Administrador\AdminTaller::desactivar');

    $routes->get('calificacion',               'Administrador\AdminCalificacion::calificacion');

    $routes->post('calificacion/aceptar',      'Administrador\AdminCalificacion::aceptar');

    $routes->post('calificacion/rechazar',     'Administrador\AdminCalificacion::rechazar');

    $routes->get('reporte',                    'Administrador\AdminReporte::Reporte');

    $routes->post('reporte/generar',           'Administrador\AdminReporte::generarReporte');

    $routes->get('salir',                      'Autentificar\Ingresar::salir');
});



//RUTAS CARPETA PROPIETARIO
$routes->group('propietario', ['filter' => 'auth:2'], static function($routes) {
//parte del perfil principal
    $routes->get( 'panel',                   'Propietario\PanelPro::dashboard');

    $routes->post('perfil/actualizar',       'Propietario\PanelPro::actualizarPerfil'); 

    $routes->get('notificacion',             'Propietario\ProNotificacion::notificacion');
 //   
//parte de la gestion del vehiculo
    $routes->get('vehiculo',                 'Propietario\ProVehiculo::vehiculo');

    $routes->post('guardar-licencia',        'Propietario\ProVehiculo::guardarLicencia'); 

    $routes->post('guardar-vehiculo',        'Propietario\ProVehiculo::guardarVehiculo');

    $routes->post('guardar-documentos',      'Propietario\ProVehiculo::guardarDocumentos');

    $routes->post('activar-vehiculo',        'Propietario\ProVehiculo::activarVehiculo');

    $routes->post('desactivar-vehiculo',     'Propietario\ProVehiculo::desactivarVehiculo');
//
//parte de la gestion califiacion y consultar taller y cita
    $routes->get('taller',                   'Propietario\ProTaller::taller');

    $routes->post('taller/calificar',        'Propietario\ProTaller::calificar');

     $routes->post('taller/actualizar',      'Propietario\ProTaller::actualizar'); 

    $routes->post('taller/eliminar',         'Propietario\ProTaller::eliminar');

    $routes->get('cita',                     'Propietario\ProCita::cita');

    $routes->post('taller/agendar-cita',     'Propietario\ProTaller::agendarCita');
//
    $routes->post('cita/cancelar',           'Propietario\ProCita::cancelar');
    
    $routes->get('salir',                    'Autentificar\Ingresar::salir');
});



// RUTAS CARPETA MECANICO
$routes->group('mecanico', ['filter' => 'auth:3'], static function($routes) {
    $routes->get( 'panel',                     'Mecanico\PanelMecanico::dashboard');

    $routes->post('perfil/actualizar',         'Mecanico\PanelMecanico::actualizarPerfil');
//parte del taller registrar, consultar, actualizar
    $routes->get( 'taller',                    'Mecanico\MecanicoTaller::taller');

    $routes->post('taller/registrar',          'Mecanico\MecanicoTaller::registrar');

    $routes->post('taller/desactivar',         'Mecanico\MecanicoTaller::desactivar'); 

    $routes->post('taller/activar',             'Mecanico\MecanicoTaller::activar'); 

    $routes->post('taller/actualizar',          'Mecanico\MecanicoTaller::actualizar'); 
//
//parte de agenda de citas pasos inciales cancelar confirmar, verificar codgio para inicio de atencion
    $routes->get( 'cita',                       'Mecanico\MecanicoCita::cita');

    $routes->post('cita/confirmar',             'Mecanico\MecanicoCita::confirmar');

    $routes->post('cita/cancelar',               'Mecanico\MecanicoCita::cancelar');

    $routes->post('cita/verificar-codigo',       'Mecanico\MecanicoCita::verificarCodigo');
//
//parte dos de la cita el seguimiento de la cita en su reparacion y finalizacion donde hay tecnicos, cerrar cita verificar el codigo para finalizar cita y guardar ese registro
    $routes->get( 'control',                     'Mecanico\MecanicoControl::control');
 
   $routes->post('control/agregar-tecnico',      'Mecanico\MecanicoControl::agregarTecnico');

   $routes->post('control/eliminar-tecnico',     'Mecanico\MecanicoControl::eliminarTecnico');

   $routes->post('control/cerrar',               'Mecanico\MecanicoControl::cerrar');

   $routes->post('control/verificar-entrega',    'Mecanico\MecanicoControl::verificarEntrega');
//
   $routes->get( 'calificacion',                 'Mecanico\MecanicoCalificacion::calificacion');

   $routes->post('calificacion/marcar-visto',     'Mecanico\MecanicoCalificacion::marcarVisto');

   $routes->get( 'salir',                        'Autentificar\Ingresar::salir');
});


