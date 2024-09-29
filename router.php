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
        exit; // Important to stop the execution immediately
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim($_SERVER['REQUEST_URI'], '/');
    
        foreach ($this->routes as $route) {
            // Adjustment for optional constraints on parameters
            $pattern = preg_replace_callback('/@([\w]+)(\[(\d+)(!)?\])?/', function($matches) {
                $param = $matches[1];
                $length = isset($matches[3]) ? (int)$matches[3] : null;
                $exact = isset($matches[4]);
    
                if ($length) {
                    // If an exact length is required (with '!')
                    if ($exact) {
                        return "([a-zA-Z0-9]{" . $length . "})";
                    } 
                    // If up to a certain length is allowed (without '!')
                    else {
                        return "([a-zA-Z0-9]{1," . $length . "})";
                    }
                }
    
                // Default case without constraint
                return "([^/]+)";
            }, trim($route['pattern'], '/'));
    
            if ($method === $route['method'] && preg_match("#^$pattern$#", $uri, $matches)) {
                array_shift($matches);
                list($controller, $action) = explode('->', $route['controllerAction']);
                $controller = new $controller;
                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }
    
        // Error handling
        $this->handleError(404);
    }

    private function handleError($statusCode) {
        foreach ($this->errorRoutes as $errorRoute) {
            // Replace all placeholders with regular expressions
            $pattern = preg_replace_callback('/@(\w+)/', function($matches) {
                return '(\d+)'; // Convert placeholder to a regular expression
            }, trim($errorRoute['pattern'], '/'));
    
            if (preg_match("#^$pattern$#", $statusCode, $matches)) {
                array_shift($matches); // Remove the first match (status code)
                list($controller, $action) = explode('->', $errorRoute['controllerAction']);
                $controller = new $controller;
                call_user_func_array([$controller, $action], array_merge($matches, [$statusCode])); // Füge den Statuscode als Parameter hinzu
                return;
            }
        }
    
        // Fallback for unknown errors
        echo "Error: $statusCode Not Found";
    }
}
