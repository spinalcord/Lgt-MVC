<?php
class Router {
    private $routes = [];
    private $errorRoutes = [];
    private $idRouteMap = []; 

    public function route($method, $pattern, $controllerAction, $id = null) {
        if ($method === 'ERROR') {
            $this->errorRoutes[] = compact('pattern', 'controllerAction');
        } else {
            $this->routes[] = compact('method', 'pattern', 'controllerAction');

            if ($id !== null) {
                if (!isset($this->idRouteMap[$id])) {
                    $this->idRouteMap[$id] = [];
                }
                $this->idRouteMap[$id][] = $pattern; 
            }
        }
    }

    public function getRoutesById($id) {
        if (isset($this->idRouteMap[$id])) {
            return $this->idRouteMap[$id];
        }
        return null; 
    }

    public static function reroute($url) {
        header("Location: $url");
        exit;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim($_SERVER['REQUEST_URI'], '/');

        foreach ($this->routes as $route) {
            $pattern = preg_replace_callback('/@([\w]+)(\[(\d+)(!)?\])?/', function($matches) {
                $param = $matches[1];
                $length = isset($matches[3]) ? (int)$matches[3] : null;
                $exact = isset($matches[4]);

                if ($length) {
                    if ($exact) {
                        return "([a-zA-Z0-9]{" . $length . "})";
                    } else {
                        return "([a-zA-Z0-9]{1," . $length . "})";
                    }
                }
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

        $this->handleError(404);
    }

    private function handleError($statusCode) {
        foreach ($this->errorRoutes as $errorRoute) {
            $pattern = preg_replace_callback('/@(\w+)/', function($matches) {
                return '(\d+)';
            }, trim($errorRoute['pattern'], '/'));

            if (preg_match("#^$pattern$#", $statusCode, $matches)) {
                array_shift($matches);
                list($controller, $action) = explode('->', $errorRoute['controllerAction']);
                $controller = new $controller;
                call_user_func_array([$controller, $action], array_merge($matches, [$statusCode]));
                return;
            }
        }

        echo "Error: $statusCode Not Found";
    }

    public static function message($reasonTitle = '', $reasonContent = '', bool $headBackButton = false) {
        $_SESSION['message'] = [
            'title' => $reasonTitle,
            'content' => $reasonContent,
            'headBackButton' => $headBackButton
        ];
        self::reroute('/message');
    }
}
