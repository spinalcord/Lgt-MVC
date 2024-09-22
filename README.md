# Lgt-MVC
A lightweight fast MVC like framework written in php. Please leave a star :).

## File Structure 
- `router.php`: Contains the Router class responsible for routing requests.
- `.htaccess`: Provides security. Required to translate the routing URL.
- `autoloader.php`: Loads the controllers.
- `index.php`: The front controller that initializes settings, loads dependencies, and defines routes.
- `HomeController.php`: Example controller demonstrating various actions.
- `Formular.html`: Example template
- `Home.html`: Example template
- `Db.php`: Provides database-specific operations.

```
.
├── App
│   ├── Controllers
│   │   └── HomeController.php (Example)
│   ├── Database
│   │   └── Database.db (Generated)
│   ├── Models
│   │   └── Db.php (Required)
│   ├── View.php   (Required)
│   └── Views
│       ├── Formular.html (Example)
│       └── Home.html     (Example)
├── autoloader.php (Required)
├── index.php      (Required)
└── router.php     (Required)
```

# index.php (Front Controller)

#### Settings 
- You can enable error reporting.
- You can choose whether to use SQLite or MySQL.
#### Global functions 
- Reduces a bit of boilerplate code. Just use the functions in any controller.
#### Routing (GET) 
- Routing in this framework is somewhat similar to the routing in the "Fat-Free Framework".
- Routing works like this:
```php
$router->route('GET', '/', 'App\Controllers\HomeController->index');
```

- You can also add parameters:
```php
$router->route('GET', '/test/@someName/@somethingElse', 'App\Controllers\HomeController->testUrlParameter');
```

To process the parameters in the controller, you just have to add them as method parameters:

```php
public function testUrlParameter($someName, $somethingElse) 
{
    echo "Parameter 1: $someName and Parameter 2: $somethingElse";
}
```

#### Routing (POST) 
 
- **First scenario** 
Usually, you have a form where you can execute the POST method with a submit button:


```html
<form method="post" action="">  
    <button type="submit">Test POST method</button>  
</form>
```
If the `action` is empty, the POST request will be sent to the same URL where you are. So if you are on
`https://mydomain.com/`, which is `/` in the routing definition, the following will be executed if you have defined it:

```php
$router->route('POST', '/', 'App\Controllers\HomeController->postTest');
```
 
- **Second scenario** 
For instance, you are on the URL `https://mydomain.com/test/abc/123`, but `abc` and `123` can have different values, and you also have the same form on this URL:


```html
<form method="post" action="">  
    <button type="submit">Test POST method</button>  
</form>
```

If you want to correctly handle the POST request, you can do this in the routing:


```php
$router->route('POST', '/test/@someName/@somethingElse', 'App\Controllers\HomeController->postTest2');
```
 
- **Third scenario** 
You can change the action so that the POST request data is sent to another URL. For example:


```html
<form method="post" action="/printentries">  
    <button type="submit">Test POST method</button>  
</form>
```

But don't forget to define the correct POST routing:

```php
$router->route('POST', '/printentries', 'App\Controllers\HomeController->printAllDbTableEntries');
```

# Database functions
- `Db.php`: This file provides database functions, like creating tables, inserting data, updating, and deleting records.
- You can use this on every controller.
## Basic Usage 
1. **Create Table** You can create a table using `db()::createTable()`. The first argument is the table name, and the second is an array of column definitions.**Example:** 

```php
db()::createTable('users', [
    'id INTEGER PRIMARY KEY AUTOINCREMENT',
    'username TEXT NOT NULL UNIQUE',
    'password TEXT NOT NULL'
]);
```
2. **Insert Data** To add a new record to a table, use `db()::insert()`. The first argument is the table name, and the second is an associative array of the data.**Example:** 

```php
db()::insert('users', [
    'username' => 'someUser',
    'password' => uniqid()
]);
```
3. **Update Data** To update an existing record, use `db()::update()`. The first argument is the table name, the second is the data to update, and the third is the record's ID.**Example:** 

```php
db()::update('users', [
    'username' => 'newUserName'
], 1);
```
4. **Load Data** To load a single record by its ID, use `db()::load()`.**Example:** 

```php
$user = db()::load('users', 1);
```
5. **Delete Data** To delete a record, use `db()::delete()` with the table name and the ID of the record.**Example:** 

```php
db()::delete('users', 1);
```
6. **Get All Records** To retrieve all records from a table, use `db()::all()`.**Example:** 

```php
$users = db()::all('users');
```
7. **Find Records by Condition** To get all records that match a certain condition, use `db()::allWhere()` with the table name, column name, and the value to match.**Example:** 

```php
$users = db()::allWhere('users', 'username', 'someUser');
```
8. **Paginate Results** You can paginate results with `db()::pages()`. The first argument is the table name, and the second is the number of records per page.**Example:** 

```php
$pages = db()::pages('users', 10);
```
9. **Paginate Results with Condition** You can paginate filtered results with `db()::pagesWhere()`. It works like `allWhere()`, but it divides the results into pages.**Example:** 

```php
$pages = db()::pagesWhere('users', 10, 'username', 'someUser');
```

# Template Engine Considerations
- This framework does not include a traditional template engine.
- By using PHP directly, you can take advantage of its great performance.
- I made a conscious choice to forgo a template engine, and here’s why.
- While template engines can offer improved readability, they may also present challenges when it comes to extending functionality and can impact performance.
- In many cases, the added complexity of a template engine may not be necessary, especially when a slight increase in readability can be achieved through other means.

Look how ridiculous it is just for little bit of readability:

**Example 1 (Variables)**:
Some Template Engine: 
`{{ @myVar }}` 
PHP: 
`<?= $myVar ?>`

**Example 2 (If-Statement)**:
Some Template Engine: 
```
{% if online == false %}
some Test
{% endif %}
```
PHP: 
```php
<?php if ($online == false): ?>
some Test
<?php endif; ?>
```

**Example 3 (Foreach-Loop)**:
Some Template Engine: 
```
{% for user in users %}
<li>{{ user.username }}</li>
{% endfor %}
```
PHP: 
```php
<?php foreach ($users as $user): ?>
<li><?= $user['username']?></li>
<?php endforeach; ?>
```

# Using Templates
- You can define variables in the specific controller:

```php
public function index() {
    // Set "View" variables
    set('title', 'Welcome to My Site');
    set('some_condition', true);
    set('custom_file', 'Formular.html');
    set('some_array', ['user1' => 'foo',"albert" => 'asdf',"max" => 'blub']);
    set('another_array', ['user1' => 1,"albert" => 2,"max" => 3]);
    render('Home');
}
```

- and you can use that in your templates. For instances in Home.html

```html
<!-- Variable test -->
<h1><?= $title ?></h1>

<!-- If test -->
<?php if ($some_condition == true): ?>
    <p>Condition is true!</p>
<?php else: ?>
    <p>Condition is false!</p>
<?php endif; ?>
```