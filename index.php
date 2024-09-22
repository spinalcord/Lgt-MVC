
<?php
////////////////////////////////////////////////
// Settings
////////////////////////////////////////////////

// Errorhandling
error_reporting(E_ALL); // Show all errors
ini_set('display_errors', 1); // Show all errors in browser

// Database setup
$dbType = 'sqlite'; // (sqlite or mysql)
$dbName = 'Database.db'; // Databasename
$dbUser = 'username'; // MySQL-User
$dbPassword = 'password'; // MySQL-Passwort

////////////////////////////////////////////////
// Load requierments
////////////////////////////////////////////////

include 'autoloader.php';
include 'router.php';

////////////////////////////////////////////////
// Global functions, can be used in controllers
// for instance: set('title', 'Welcome');
////////////////////////////////////////////////

function db() {
    return App\Models\Db::class;
} db()::connect();

function set($key, $value) {
    App\View::set($key, $value);
}

function render($template) {
    App\View::render($template);
}

function reroute($url) {
    Router::reroute($url);
}

function listRoutes() {
    return $GLOBALS['router']->listRoutes(); // Rückgabe der Routen-Liste als String
}

////////////////////////////////////////////////
// Routing
////////////////////////////////////////////////

$router = new Router();

// Define routes
$router->route('ERROR', '/@statuscode', 'App\Controllers\HomeController->errorHandling');
$router->route('GET', '/', 'App\Controllers\HomeController->index');
$router->route('GET', '/insertentry', 'App\Controllers\HomeController->insertDbTableEntry');
$router->route('GET', '/listroutes', 'App\Controllers\HomeController->listRoutesTest');
$router->route('GET', '/printentries', 'App\Controllers\HomeController->printAllDbTableEntries');
$router->route('GET', '/reroutetest', 'App\Controllers\HomeController->rerouteTest');
$router->route('GET', '/test/@someName/@somethingElse', 'App\Controllers\HomeController->testUrlParameter');
$router->route('POST', '/', 'App\Controllers\HomeController->postTest');
$router->route('POST', '/test/@someName/@somethingElse', 'App\Controllers\HomeController->postTest2');


// Execute routing
$router->dispatch();

// render('Home'); // Uncomment if you wan't render 'Home' on every page

