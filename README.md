# Lgt-MVC 

A lightweight, fast, MVC-like framework written in PHP. Please leave a star ⭐ 😅.

## File Structure 
 
- `router.php`: Contains the `Router` class, responsible for handling routing of requests.
- `.htaccess`: Ensures security and is necessary for translating the routing URLs.
- `autoloader.php`: Automatically loads the controllers.
- `index.php`: The front controller that initializes settings, loads dependencies, and defines routes.
- `HomeController.php`: An example controller demonstrating various actions.
- `Formular.html`: Example template file.
- `Home.html`: Example template file.
- `Db.php`: Provides database-specific operations and utility functions.
- `Language.php`: Provides a function to retrieve translated strings.
- `en.php`: English language translation file.
- `de.php`: German language translation file.


```text
.
├── App
│   ├── Controllers
│   │   └── HomeController.php
│   ├── Database
│   │   └── Database.db
│   ├── Languages
│   │   ├── de.php
│   │   └── en.php
│   ├── Models
│   │   ├── Db.php
│   │   └── Language.php
│   ├── View.php
│   └── Views
│       ├── Formular.html
│       └── Home.html
├── autoloader.php
├── index.php
├── README.md
└── router.php

```
`index.php` (Front Controller)
#### Settings 

- Enable error reporting for development or debugging.

- Choose default language.

- Choose whether to use SQLite or MySQL for database operations.

#### Global Functions 

- Simplify your controller code by using global functions, reducing boilerplate.

#### Routing (GET) 

- Routing is similar to the Fat-Free Framework.

- Example of routing:


```php
$router->route('GET', '/', 'App\Controllers\HomeController->index');
```

- You can also pass parameters in the URL:


```php
$router->route('GET', '/test/@someName/@somethingElse', 'App\Controllers\HomeController->testUrlParameter');
```

In the controller, handle these parameters by adding them as method arguments:


```php
public function testUrlParameter($someName, $somethingElse) 
{
    echo "Parameter 1: $someName and Parameter 2: $somethingElse";
}
```

It is also possible to limit parameters, for instance, using `@someName[8]`. This means that the parameter can have fewer than or exactly 8 characters. Any other input will result in a 404 error.

```php
$router->route('GET', '/test/@someName[8]/@somethingElse', 'App\Controllers\HomeController->testUrlParameter');
```

You can also specify an exact length with `@someName[8!]`. This means that the parameter must be exactly 8 characters long. Any other input will result in a 404 error.

```php
$router->route('GET', '/test/@someName[8!]/@somethingElse', 'App\Controllers\HomeController->testUrlParameter');
```

#### Routing (POST) 
 
- **Scenario 1: Basic POST Form** 
Typically, you'll use a form to submit POST requests:


```html
<form method="post" action="">  
    <button type="submit">Test POST method</button>  
</form>
```
If the `action` attribute is empty, the POST request will be sent to the same URL. For example, if you are at `https://mydomain.com/`, this routing definition will handle the request:

```php
$router->route('POST', '/', 'App\Controllers\HomeController->postTest');
```
 
- **Scenario 2: Dynamic URL Parameters** 
If you are on a URL like `https://mydomain.com/test/abc/123`, where `abc` and `123` can vary, and you submit a form on this page:


```html
<form method="post" action="">  
    <button type="submit">Test POST method</button>  
</form>
```

Define the routing to correctly handle dynamic parameters:


```php
$router->route('POST', '/test/@someName/@somethingElse', 'App\Controllers\HomeController->postTest2');
```
 
- **Scenario 3: Custom POST Action** 
You can also send the POST request to a different URL:


```html
<form method="post" action="/printentries">  
    <button type="submit">Test POST method</button>  
</form>
```

Just ensure you have defined the proper routing for the POST request:


```php
$router->route('POST', '/printentries', 'App\Controllers\HomeController->printAllDbTableEntries');
```

#### Rerouting 

- Sometimes you need to redirect (reroute) to another URL.
 
- You can reroute from within any controller method using `reroute('...')`:


```php
public function rerouteTest()
{
    reroute('/printentries');
}
```

#### Error Handling (Routing Errors) 

- Handling common errors such as 404 (Not Found) is straightforward:


```php
$router->route('ERROR', '/@statuscode', 'App\Controllers\HomeController->errorHandling');
```

In the controller, process the error code like this:


```php
public function errorHandling($statusCode) {
    http_response_code($statusCode);
    echo "Oh no! Error code: $statusCode"; 
}
```

#### Route Debugging 

- List all defined routes for debugging purposes:


```php
public function listRoutesTest()
{
    echo listRoutes();
}
```

# Database Functions 
 
- `Db.php`: This file provides database functions for creating tables, inserting, updating, and deleting records.

- These functions are available to use in any controller.

## Basic Usage 
 
1. **Create Table** 
Use `db()::createTable()` to create a table. The first argument is the table name, and the second is an array defining the columns.
**Example:**


```php
db()::createTable('users', [
    'id INTEGER PRIMARY KEY AUTOINCREMENT',
    'username TEXT NOT NULL UNIQUE',
    'password TEXT NOT NULL'
]);
```
 
1. **Insert Data** 
Use `db()::insert()` to add a record. The first argument is the table name, and the second is an associative array of the data.
**Example:**


```php
db()::insert('users', [
    'username' => 'someUser',
    'password' => uniqid()
]);
```
 
1. **Update Data** 
Use `db()::update()` to modify a record. The first argument is the table name, the second is the data, and the third is the record's ID.
**Example:**


```php
db()::update('users', [
    'username' => 'newUserName'
], 1);
```
 
1. **Load Data** 
Use `db()::load()` to retrieve a record by its ID.
**Example:**


```php
$user = db()::load('users', 1);
```
 
1. **Delete Data** 
Use `db()::delete()` to remove a record by its ID.
**Example:**


```php
db()::delete('users', 1);
```
 
1. **Get All Records** 
Use `db()::all()` to retrieve all records from a table.
**Example:**


```php
$users = db()::all('users');
```
 
1. **Find Records by Condition** 
Use `db()::allWhere()` to retrieve all records that match a certain condition.
**Example:**


```php
$users = db()::allWhere('users', 'username', 'someUser');
```
 
1. **Paginate Results** 
Use `db()::pages()` to paginate the results, with the first argument being the table name and the second the number of records per page.
**Example:**


```php
$pages = db()::pages('users', 10);
```
 
1. **Paginate Results with Condition** 
Use `db()::pagesWhere()` to paginate filtered results. It works similarly to `allWhere()`, but divides results into pages.
**Example:**


```php
$pages = db()::pagesWhere('users', 10, 'username', 'someUser');
```

# Template Engine Considerations 

- This framework does not include a traditional template engine. Instead, you can use PHP directly for rendering views.

- The decision to avoid a template engine was made for performance reasons and to reduce complexity. While template engines can offer some readability improvements, they can also introduce overhead and limitations.
**Comparison Examples** :

**Example 1 (Variables)** :
Some Template Engine:
`{{ @myVar }}`
PHP:
`<?= $myVar ?>`

**Example 2 (If-Statement)** :
Some Template Engine:

```twig
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
**Example 3 (Foreach-Loop)** :
Some Template Engine:

```twig
{% for user in users %}
<li>{{ user.username }}</li>
{% endfor %}
```

PHP:


```php
<?php foreach ($users as $user): ?>
<li><?= $user['username'] ?></li>
<?php endforeach; ?>
```

# Using Templates 
> **Important:** Firstly, make sure you set base in your template html to prevent src errors


```html
<head>
    <base href="/">
</head>
```

- You can define variables in the controller and pass them to your templates:


```php
public function index() {
    // Set "View" variables
    set('title', 'Welcome to My Site');
    set('some_condition', true);
    set('custom_file', 'Formular.html');
    set('some_array', ['user1' => 'foo', 'albert' => 'asdf', 'max' => 'blub']);
    set('another_array', ['user1' => 1, 'albert' => 2, 'max' => 3]);
    render('Home');
}
```
 
- Use these variables in your templates (e.g., `Home.html`):


```html
<!-- Variable example -->
<h1><?= $title ?></h1>

<!-- If example -->
<?php if ($some_condition): ?>
    <p>Condition is true!</p>
<?php else: ?>
    <p>Condition is false!</p>
<?php endif; ?>
```

# Language Support 
 
- Language files are stored in `App/Languages/`.
 
- For example, `en.php` contains English translations.
 
- You can use placeholders like `%s` for dynamic values.
`en.php` example:

```php
<?php

return [
    'wrong_captcha' => 'Captcha input is incorrect.',
    'content_successfully_inserted' => 'Content "%s" with ID "%s" was successfully inserted.',
    'label_image_preview' => 'Image preview'
];
```

#### Usage Example 
 
- Retrieve translations with `language()::getTranslation('...')`:


```php
public function translateTest() 
{
    echo language()::getTranslation('wrong_captcha');
    echo language()::getTranslation('content_successfully_inserted', ['parameter1 Test', 'parameter2 Test :)']);
}
```

#### Change Language
- you can change the default language in the index.php.
- you can change the client language with `language()::setClientLanguage('de')` on any controller.