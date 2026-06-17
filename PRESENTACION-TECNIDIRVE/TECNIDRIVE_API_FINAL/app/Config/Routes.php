<?php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'AuthWeb::ingreso');

// ── Vistas web (fuera del grupo API) ──────────────────────────────────
$routes->get('registro', 'AuthWeb::registro');
$routes->get('ingreso',  'AuthWeb::ingreso');

// ── API REST ───────────────────────────────────────────────────────────
$routes->group('api/v1', function ($routes) {
    $routes->post('registro', 'AuthApi::registro');
    $routes->post('login',    'AuthApi::login');

    $routes->group('', ['filter' => 'jwt'], function ($routes) {
        $routes->resource('usuarios');
        $routes->resource('vehiculos');
        $routes->resource('talleres');
    });
});