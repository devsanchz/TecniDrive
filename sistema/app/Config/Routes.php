<?php
use CodeIgniter\Router\RouteCollection;
/** 
*@var RouteCollection $routes
*/
$routes->setAutoRoute(false);

//index o login :

$routes->get('/','home::index'); // la vista

$routes->post('login', 'Login::roles');

#en caso de que los datos ingresados del login esten mal

$routes->get('dashboard','login::volverLogin');
$routes->get('dashboard','login::volverLogin');



/**
 * Tener en cuenta algo
 * al momento de usar estas funcines de rutas siempre empieza con $routes
 * pero luego de la flecha hay diferentes usos get para tomar datos post para enviarlos
 * $routes -> procedimiento('aqui va la direccion de envio de datos','nombre del controlador :: funcion ')
 * 
 */