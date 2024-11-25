<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/employees', 'EmployeeController::index');
$routes->get('employees/getJobs/(:num)', 'EmployeeController::getJobs/$1');
$routes->post('employees/addJob', 'EmployeeController::addJob');
$routes->post('jobs/delete', 'EmployeeController::deleteJob');
$routes->post('employees/addEmployee', 'EmployeeController::addEmployee');
$routes->post('employees/delete', 'EmployeeController::deleteEmployee');