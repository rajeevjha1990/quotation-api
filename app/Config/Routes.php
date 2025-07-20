<?php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Auth APIs (login, get user)
$routes->group('api/auth', function($routes) {
    $routes->post('login', 'Api\Auth::login');
    $routes->post('get_user', 'Api\Auth::get_user');
    $routes->post('logout', 'Api\Auth::logout');
});

$routes->group('api/item', function($routes) {
      $routes->post('item_list', 'Api\Item::item_list');
      $routes->post('new_item', 'Api\Item::new_item');
});

$routes->group('api/client', function($routes) {
      $routes->post('client_list', 'Api\Client::client_list');
      $routes->post('new_client', 'Api\Client::new_client');
      $routes->post('client_current_quotation', 'Api\Client::client_current_quotation');
});

$routes->group('api/quotation', function($routes) {
    $routes->post('new_quotation', 'Api\Quotation::new_quotation');
    $routes->get('quotationPDF/(:num)/(:num)', 'Api\Quotation::quotationPDF/$1/$2');
});





/*Testing For only php based Application*/
//Dashboard APIs (login required)
$routes->group('api/dashboard', function($routes) {
    $routes->get('/', 'Api\Dashboard::index');
});

// Public APIs (no login)
$routes->group('api/public', function($routes) {
    $routes->get('ping', 'Api\PublicApi::ping');
});
