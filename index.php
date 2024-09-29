
<?php




include 'settings.php';
include 'autoloader.php';
include 'router.php';
include 'functions.php';





$router = new Router();


$router->route('ERROR', '/@statuscode', 'App\Controllers\HomeController->errorHandling');
$router->route('GET', '/', 'App\Controllers\HomeController->index');
$router->route('GET', '/insertentry', 'App\Controllers\HomeController->insertDbTableEntry');
$router->route('GET', '/printentries', 'App\Controllers\HomeController->printAllDbTableEntries');
$router->route('GET', '/reroutetest', 'App\Controllers\HomeController->rerouteTest');
$router->route('GET', '/test/@someName/@somethingElse', 'App\Controllers\HomeController->testUrlParameter');
$router->route('GET', '/translate', 'App\Controllers\HomeController->translateTest');
$router->route('POST', '/', 'App\Controllers\HomeController->postTest');
$router->route('POST', '/test/@someName/@somethingElse', 'App\Controllers\HomeController->postTest2');



$router->dispatch();



