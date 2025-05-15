<?php

namespace Config;

$routes = Services::routes();

$routes->get('inventory', 'Inventory::index', ['namespace' => 'Inventory\Controllers']);
$routes->post('inventory', 'Inventory::index', ['namespace' => 'Inventory\Controllers']);

$routes->get('inventory', 'Inventory::model_form', ['namespace' => 'Inventory\Controllers']);
$routes->post('inventory', 'Inventory::model_form', ['namespace' => 'Inventory\Controllers']);
$routes->get('/model-form', 'FormController::model_form');

$routes->get('inventory/(:any)', 'Inventory::$1', ['namespace' => 'Inventory\Controllers']);
$routes->post('inventory/(:any)', 'Inventory::$1', ['namespace' => 'Inventory\Controllers']);

$routes->get('inventory_settings', 'Inventory_settings::index', ['namespace' => 'Inventory\Controllers']);
$routes->get('inventory_settings/(:any)', 'Inventory_settings::$1', ['namespace' => 'Inventory\Controllers']);
$routes->post('inventory_settings/(:any)', 'Inventory_settings::$1', ['namespace' => 'Inventory\Controllers']);
