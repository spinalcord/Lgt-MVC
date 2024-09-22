<?php
class Router {
    private $routes = [];

    private $errorRoutes = [];

    public function route($method, $pattern, $controllerAction) {
        if ($method === 'ERROR') {
            $this->errorRoutes[] = compact('pattern', 'controllerAction');
        } else {
            $this->routes[] = compact('method', 'pattern', 'controllerAction');
        }
    }

    public static function reroute($url) {
        header("Location: $url");
        exit; // Wichtig, um die Ausführung sofort zu stoppen
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim($_SERVER['REQUEST_URI'], '/');

        foreach ($this->routes as $route) {
            $pattern = preg_replace('/@([\w]+)/', '([^/]+)', trim($route['pattern'], '/'));

            if ($method === $route['method'] && preg_match("#^$pattern$#", $uri, $matches)) {
                array_shift($matches);
                list($controller, $action) = explode('->', $route['controllerAction']);
                $controller = new $controller;
                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }

        // Wenn keine Route gefunden wurde, Fehlerbehandlung aufrufen
        $this->handleError(404);
    }

    private function handleError($statusCode) {
        foreach ($this->errorRoutes as $errorRoute) {
            // Ersetze alle Platzhalter durch reguläre Ausdrücke
            $pattern = preg_replace_callback('/@(\w+)/', function($matches) {
                return '(\d+)'; // Platzhalter in einen regulären Ausdruck umwandeln
            }, trim($errorRoute['pattern'], '/'));
    
            if (preg_match("#^$pattern$#", $statusCode, $matches)) {
                array_shift($matches); // Entferne den ersten Match (Statuscode)
                list($controller, $action) = explode('->', $errorRoute['controllerAction']);
                $controller = new $controller;
                call_user_func_array([$controller, $action], array_merge($matches, [$statusCode])); // Füge den Statuscode als Parameter hinzu
                return;
            }
        }
    
        // Fallback für unbekannte Fehler
        echo "Error: $statusCode Not Found";
    }
    

    // Returns the defined routes as links
    public function listRoutes() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = "$protocol://$host";

        $output = ''; // Prepare a string for output

        foreach ($this->routes as $route) {
            $pattern = $route['pattern'];
            
            // Replace @parameter with a random ID (instead of ([^/]+))
            $pattern = preg_replace_callback('/@([\w]+)/', function() {
                return $this->generateUniqueId();;
            }, $pattern);

            $pattern = str_replace('([^/]+)', $this->generateUniqueId(), $pattern);

            // Generate links as HTML tags
            $url = "$baseUrl/$pattern";
            $output .= "({$route['method']}) <a href=\"$url\">$url</a><br>";
        }

        return $output; // Return the string
    }

    // Function to generate a random, unique ID
    private function generateUniqueId() {
        return bin2hex(random_bytes(8));;
    }
}

