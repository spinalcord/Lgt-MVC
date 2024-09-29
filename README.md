# Lgt-MVC 

A lightweight, fast, MVC-like framework written in PHP. Please leave a star ⭐ 😅.

## File Structure 
 
- `router.php`: Contains the `Router` class, responsible for handling routing of requests.
- `.htaccess`: Ensures security and is necessary for translating the routing URLs.
- `autoloader.php`: Automatically loads the controllers.
- `index.php`: The front controller that initializes settings, loads dependencies, and defines routes.
- `settings.php`: Setup settings.
- `functions.php`: Globals functions to avoid boilerplate code.
- `homeController.php`: An example controller demonstrating various actions.
- `formular.html`: Example template file.
- `home.html`: Example template file.
- `db.php`: Provides database-specific operations and utility functions.
- `language.php`: Provides a function to retrieve translated strings.
- `en.php`: English language translation file.
- `de.php`: German language translation file.


```text
.
├── app
│   ├── controllers
│   │   └── HomeController.php
│   ├── database
│   │   └── Database.db
│   ├── languages
│   │   ├── de.php
│   │   └── en.php
│   ├── models
│   │   ├── db.php
│   │   └── language.php
│   ├── view.php
│   └── views
│       ├── formular.html
│       └── home.html
├── App
│   ├── controllers
│   │   └── HomeController.php
│   ├── database
│   │   └── Database.db
│   ├── languages
│   │   ├── de.php
│   │   └── en.php
│   ├── models
│   │   ├── db.php
│   │   └── language.php
│   ├── view.php
│   └── views
│       ├── formular.html
│       └── home.html
├── autoloader.php
├── functions.php
├── index.php
├── router.php
└── settings.php
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



# Database Functions 
 
- `db.php`: This file provides database functions for creating tables, inserting, updating, and deleting records.

- These functions are available to use in any controller.

## Basic Usage 
 

## Table of Contents
- [connect()](#connect)
- [createTable()](#createtable)
- [exists()](#exists)
- [insert()](#insert)
- [update()](#update)
- [delete()](#delete)
- [deleteAll()](#deleteall)
- [select()](#select)
- [all()](#all)
- [allWhere()](#allwhere)
- [pages()](#pages)
- [pagesWhere()](#pageswhere)
- [latestEntries()](#latestentries)

---

### `connect()`

Establishes a connection to the database.

**Usage Example:**
```php
db()::connect();
```


---

`createTable($tableName, $columns)`
Creates a table if it doesn't exist.
**Parameters:**  
- `$tableName`: The name of the table to create.
 
- `$columns`: An array of column definitions.
**Usage Example:** 

```php
db()::createTable('users', ['id INT PRIMARY KEY', 'name VARCHAR(100)']);
```


---

`exists($table, $column, $value)`
Checks if a value exists in a specific column of a table.
**Parameters:**  
- `$table`: The table to check.
 
- `$column`: The column to check.
 
- `$value`: The value to look for.
**Usage Example:** 

```php
$userExists = db()::exists('users', 'username', 'john_doe');
```


---

`insert($table, $data)`
Inserts a new row into a table.
**Parameters:**  
- `$table`: The table to insert into.
 
- `$data`: An associative array of column-value pairs.
**Usage Example:** 

```php
db()::insert('users', ['username' => 'john_doe', 'email' => 'john@example.com']);
```


---

`update($table, $data, $conditions)`
Updates existing rows in a table.
**Parameters:**  
- `$table`: The table to update.
 
- `$data`: An associative array of column-value pairs for the update.
 
- `$conditions`: An associative array of conditions to match.
**Usage Example:** 

```php
db()::update('users', ['email' => 'new_email@example.com'], ['username' => 'john_doe']);
```


---

`delete($table, $conditions = [])`
Deletes rows from a table based on conditions.
**Parameters:**  
- `$table`: The table to delete from.
 
- `$conditions`: An associative array of conditions to match.
**Usage Example:** 

```php
db()::delete('users', ['username' => 'john_doe']);
```


---

`deleteAll($table)`
Deletes all rows from a table.
**Parameters:**  
- `$table`: The table to delete all rows from.
**Usage Example:** 

```php
db()::deleteAll('users');
```


---

`select($table, $conditions = [])`
Selects a single row from a table based on conditions.
**Parameters:**  
- `$table`: The table to select from.
 
- `$conditions`: An associative array of conditions to match (optional).
**Usage Example:** 

```php
$user = db()::select('users', ['username' => 'john_doe']);
```


---

`all($table)`
Retrieves all rows from a table.
**Parameters:**  
- `$table`: The table to select from.
**Usage Example:** 

```php
$allUsers = db()::all('users');
```


---

`allWhere($table, $column, $value)`
Retrieves all rows from a table where a specific condition is met.
**Parameters:**  
- `$table`: The table to select from.
 
- `$column`: The column to check.
 
- `$value`: The value to look for.
**Usage Example:** 

```php
$usersWithName = db()::allWhere('users', 'name', 'John');
```


---

`pages($table, $page_count)`
Fetches all rows from a table and divides them into pages.
**Parameters:**  
- `$table`: The table to select from.
 
- `$page_count`: The number of rows per page.
**Usage Example:** 

```php
$usersPages = db()::pages('users', 10);
```


---

`pagesWhere($table, $page_count, $column, $value)`
Fetches rows from a table based on a condition and divides them into pages.
**Parameters:**  
- `$table`: The table to select from.
 
- `$page_count`: The number of rows per page.
 
- `$column`: The column to check.
 
- `$value`: The value to look for.
**Usage Example:** 

```php
$pages = db()::pagesWhere('users', 10, 'name', 'John');
```


---

`latestEntries($table, $datetimeColumn, $limit)`
Fetches the latest rows from a table based on a date/time column.
**Parameters:**  
- `$table`: The table to select from.
 
- `$datetimeColumn`: The column storing the date/time.
 
- `$limit`: The number of rows to retrieve.
**Usage Example:** 

```php
$latestPosts = db()::latestEntries('posts', 'created_at', 5);
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

> [!IMPORTANT]  
>  Firstly, make sure you set base in your template html to prevent src errors.

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
    set('custom_file', 'formular.html');
    set('some_array', ['user1' => 'foo', 'albert' => 'asdf', 'max' => 'blub']);
    set('another_array', ['user1' => 1, 'albert' => 2, 'max' => 3]);
    render('home');
}
```
 
- Use these variables in your templates (e.g., `home.html`):


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
 
- Language files are stored in `app/languages/`.
 
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