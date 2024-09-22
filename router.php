<?php
// Einfache Router-Klasse definieren
class Router {
    private $routes = [];

    public function route($method, $pattern, $controllerAction) {
        // Hier den Platzhalter ersetzen, um beliebige Zeichen zu erlauben
        $pattern = preg_replace('/@([\w]+)/', '([^/]+)', trim($pattern, '/'));
        $this->routes[] = compact('method', 'pattern', 'controllerAction');
    }

    public static function reroute($url) {
        header("Location: $url");
        exit; // Wichtig, um die Ausführung sofort zu stoppen
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim($_SERVER['REQUEST_URI'], '/');

        foreach ($this->routes as $route) {
            if ($method === $route['method'] && preg_match("#^{$route['pattern']}$#", $uri, $matches)) {
                array_shift($matches);
                list($controller, $action) = explode('->', $route['controllerAction']);
                $controller = new $controller;
                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }

        echo "404 Not Found";
    }
}

