<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        // Set "View" variables
        set('title', 'Welcome to My Site');
        set('some_condition', true);
        set('custom_file', 'Formular.html');
        set('some_array', ['user1' => 'foo',"albert" => 'asdf',"max" => 'blub']);
        set('another_array', ['user1' => 1,"albert" => 2,"max" => 3]);
        render('Home');
    }

    public function insertDbTableEntry()
    {
        db()::createTable('users', [
            'id INTEGER PRIMARY KEY AUTOINCREMENT',
            'username TEXT NOT NULL UNIQUE',
            'password TEXT NOT NULL'
        ]);

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
        render('Formular');
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
}
