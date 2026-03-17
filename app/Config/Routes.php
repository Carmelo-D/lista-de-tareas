<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- REDIRECCIÓN PRINCIPAL ---
// Ahora la raíz '/' ya no va a Home, sino al Login de TaskFlix
$routes->get('/', 'Auth::login');

// --- RUTAS DE AUTENTICACIÓN (Nuevas) ---
$routes->get('login', 'Auth::login');
$routes->get('registro', 'Auth::registro');
$routes->post('auth/postRegistrar', 'Auth::postRegistrar');
$routes->post('auth/postLogin', 'Auth::postLogin');
$routes->get('auth/logout', 'Auth::logout');

// --- RUTAS DE PERFILES ---
$routes->get('perfiles', 'Perfiles::index');
$routes->post('perfiles/crear', 'Perfiles::crear');
$routes->get('perfiles/seleccionar/(:num)', 'Perfiles::seleccionar/$1');
$routes->post('perfiles/verificar_pin', 'Perfiles::verificar_pin');
$routes->get('perfiles/salir', 'Perfiles::salir');
$routes->get('perfiles/eliminar/(:num)', 'Perfiles::eliminar/$1');

// --- RUTAS DE TAREAS ---
$routes->get('tareas', 'Tareas::getIndex');
$routes->post('tareas/crear', 'Tareas::postCrear');
$routes->get('tareas/completar/(:num)', 'Tareas::getCompletar/$1');
$routes->get('tareas/eliminar/(:num)', 'Tareas::getEliminar/$1');
$routes->get('tareas/editar/(:num)', 'Tareas::getEditar/$1');
$routes->post('tareas/actualizar', 'Tareas::postActualizar');
$routes->post('tareas/programar', 'Tareas::postProgramar');

$routes->post('categorias/crear', 'Categorias::postCrear');
$routes->get('categorias/eliminar/(:num)', 'Categorias::getEliminar/$1');
$routes->post('tareas/limpiar-historial', 'Tareas::postLimpiarHistorial');