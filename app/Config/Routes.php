<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'RokyC::index');
$routes->get('/roky/(:num)', 'ZavodyC::index/$1');
$routes->get('/roky/zavod/(:num)', 'RaceC::show/$1');
$routes->post('zavody/pridat', 'ZavodyC::add');
$routes->post('zavody/change', 'ZavodyC::change');
$routes->post('zavody/smazat', 'ZavodyC::delete');