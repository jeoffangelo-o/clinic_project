<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/register', 'UserController::register');
$routes->post('/store_user', 'UserController::store_user');

$routes->get('/login', 'UserController::login');
$routes->post('/auth', 'UserController::auth');

$routes->get('/logout', 'UserController::logout');

$routes->get('/list_user', 'UserController::list_user');

$routes->get('/edit_user/(:num)', 'UserController::edit_user/$1');
$routes->post('/update_user/(:num)', 'UserController::update_user/$1');



$routes->get('/patient', 'PatientController::patient');
$routes->get('/patient/add', 'PatientController::add_patient');

