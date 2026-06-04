<?php
 
use CodeIgniter\Router\RouteCollection;
 
/**
 * @var RouteCollection $routes
 */
 
$routes->get('/', 'Login::index');
$routes->post('/authenticate', 'Login::authenticate');
$routes->get('/logout', 'Login::logout');
 
$routes->get('/panel/admin', 'Home::panelAdmin', ['filter' => 'auth:Administrador']);
$routes->get('/panel/mecanico', 'Home::panelMecanico', ['filter' => 'auth:Mecanico']);
$routes->get('/panel/propietario', 'Home::panelPropietario', ['filter' => 'auth:Propietario']);
 
$routes->get('/roles', 'Home::roles');
$routes->post('/roles/guardar', 'Home::guardarRol');
 
$routes->get('/admin/talleres', 'Home::adminTalleres', ['filter' => 'auth:Administrador']);
$routes->get('/admin/vehiculos', 'Home::adminVehiculos', ['filter' => 'auth:Administrador']);
$routes->get('/admin/calificaciones', 'Home::adminCalificaciones', ['filter' => 'auth:Administrador']);
 
$routes->get('/mecanico/taller', 'Home::mecanicoTaller', ['filter' => 'auth:Mecanico']);
$routes->get('/mecanico/citas', 'Home::mecanicoCitas', ['filter' => 'auth:Mecanico']);
$routes->get('/mecanico/calificaciones', 'Home::mecanicoCalificaciones', ['filter' => 'auth:Mecanico']);
 
$routes->get('/propietario/vehiculos', 'Home::propietarioVehiculos', ['filter' => 'auth:Propietario']);
$routes->get('/propietario/talleres', 'Home::propietarioTalleres', ['filter' => 'auth:Propietario']);
$routes->get('/propietario/citas', 'Home::propietarioCitas', ['filter' => 'auth:Propietario']);
$routes->get('/propietario/calificaciones', 'Home::propietarioCalificaciones', ['filter' => 'auth:Propietario']);
$routes->get('/propietario/notificaciones', 'Home::propietarioNotificaciones', ['filter' => 'auth:Propietario']);