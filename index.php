
<?php
include 'settings.php';
include 'autoloader.php';
include 'router.php';
include 'functions.php';



Router::route('ERROR', '/@statuscode', 'App\Controllers\HomeController->errorHandling');
Router::route('GET', '/', 'App\Controllers\HomeController->index');
Router::route('GET', '/insertentry', 'App\Controllers\HomeController->insertDbTableEntry');
Router::route('GET', '/printentries', 'App\Controllers\HomeController->printAllDbTableEntries');
Router::route('GET', '/reroutetest', 'App\Controllers\HomeController->rerouteTest');
Router::route('GET', '/test/@someName/@somethingElse', 'App\Controllers\HomeController->testUrlParameter');
Router::route('GET', '/translate', 'App\Controllers\HomeController->translateTest');
Router::route('POST', '/', 'App\Controllers\HomeController->postTest');
Router::route('POST', '/test/@someName/@somethingElse', 'App\Controllers\HomeController->postTest2');



Router::dispatch();



