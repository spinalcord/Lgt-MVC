<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        
        set('title', 'Welcome to My Site');
        set('some_condition', true);
        set('custom_file', 'formular.html');
        set('some_array', ['user1' => 'foo',"albert" => 'asdf',"max" => 'blub']);
        set('another_array', ['user1' => 1,"albert" => 2,"max" => 3]);


        db()::createTable('users', [
            'id INTEGER PRIMARY KEY AUTOINCREMENT',
            'username TEXT NOT NULL UNIQUE',
            'password TEXT NOT NULL'
        ]);

        render('home');
    }

    public function insertDbTableEntry()
    {
        db()::insert('users',['username' => 'someUser','password' => uniqid()]);
        
        echo 'Entry should by inserted.';
    }

    public function printAllDbTableEntries()
    {
        foreach (db()::all('users') as $tableArray) {  
            foreach ($tableArray as $key => $value) {  
                echo $key . " - " . $value . "<br>";  
            }
        }
    }

    public function testUrlParameter($someName, $somethingElse) 
    {
        set('title', 'Test two parameter and post method');
        echo "Parameter 1: $someName and Parameter 2: $somethingElse";
        render('formular');
    }

    public function rerouteTest()
    {
        reroute('/printentries');
    }

    public function postTest()
    {
        echo "postTest passed.";
    }

    public function postTest2()
    {
        echo "postTest2 passed.";
    }

    public function errorHandling($somecode) {
        http_response_code($somecode);
        echo "Oh nein, Fehlercode: $somecode"; 
    }

    public function translateTest() {
        echo language()::getTranslation('wrong_captcha');
        echo language()::getTranslation('content_successfully_inserted', ['parameter1 Test', 'parameter2 Test :)']);
    }
}

