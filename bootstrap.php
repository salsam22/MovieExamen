<?php
require "vendor/autoload.php";

use App\Josep\Config;
use App\Registry;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;


$configXML = new \App\Alex\Config(__DIR__ . '/./config.xml');
$configJson = new \App\Josep\Config(__DIR__ . '/./config.json');

Registry::setPDO($configXML);
//Registry::setPDO($configJson);

// create a log channel
$log = new Logger('movies');
$log->pushHandler(new StreamHandler(__DIR__ . "/./app.log", Logger::DEBUG));
$log->pushHandler(new FirePHPHandler());
Registry::set(Registry::LOGGER, $log);

$router = new AltoRouter();

Registry::set(Registry::ROUTER, $router);

// map homepage
//$router->map('GET', '/', function() {
//    require __DIR__ . '/views/home.php';
//});


// map homepage
$router->map('GET', '/', 'MovieController#list', 'movie_list');

// dynamic named route
$router->map('GET|POST', '/movies/[i:id]/edit', "MovieController#edit", 'movie_edit');

$router->map('GET', '/movies/[i:id]/show', "MovieController#show", 'movie_show');

$router->map('GET|POST', '/movies/create', "MovieController#create", 'movie_create');

$router->map('GET|POST', '/movies/store', "MovieController#store", 'movie_store');

$router->map('GET|POST', '/movies/[i:id]/delete', "MovieController#delete", 'movie_delete');

$router->map('GET|POST', '/login', "UserController#login", 'login');

$router->map('GET|POST', '/register', "UserController#register", 'register');

$router->map('GET|POST', '/loggout', "UserController#loggout", 'loggout');

$match = $router->match();