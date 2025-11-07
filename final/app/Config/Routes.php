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
$routes->get('/delete_user/(:num)', 'UserController::delete_user/$1');



$routes->get('/patient', 'PatientController::patient');
$routes->get('/patient/add', 'PatientController::add_patient');
$routes->post('/patient/store', 'PatientController::store_patient');

$routes->get('/patient/view/(:num)', 'PatientController::view_patient/$1');
$routes->get('/patient/edit/(:num)', 'PatientController::edit_patient/$1');
$routes->post('/patient/update/(:num)', 'PatientController::update_patient/$1');
$routes->get('/patient/delete/(:num)', 'PatientController::delete_patient/$1');


$routes->get('/appointment', 'AppointmentController::appointment');
$routes->get('/appointment/add', 'AppointmentController::add_appointment');
$routes->get('/appointment/edit/(:num)', 'AppointmentController::edit_appointment/$1');
$routes->get('/appointment/delete/(:num)', 'AppointmentController::delete_appointment/$1');
$routes->post('/appointment/store', 'AppointmentController::store_appointment');
$routes->post('/appointment/update/(:num)', 'AppointmentController::update_appointment/$1');


$routes->get('/consultation', 'ConsultationController::consultation');
$routes->get('/consultation/add', 'ConsultationController::add_consultation');
$routes->post('/consultation/store', 'ConsultationController::store_consultation');
$routes->get('/consultation/edit/(:num)', 'ConsultationController::edit_consultation/$1');
$routes->post('/consultation/update/(:num)', 'ConsultationController::update_consultation/$1');
$routes->get('/consultation/delete/(:num)', 'ConsultationController::delete_consultation/$1');


$routes->get('/announcement', 'AnnouncementController::announcement');
$routes->get('/announcement/add', 'AnnouncementController::add_announcement');
$routes->post('/announcement/store', 'AnnouncementController::store_announcement');
