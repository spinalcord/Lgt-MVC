<?php
class Router {
    private static $routes = [];
    private static $errorRoutes = [];
    private static $idRouteMap = [];

    public static function route($method, $pattern, $controllerAction, $id = null) {
        if ($method === 'ERROR') {
            self::$errorRoutes[] = compact('pattern', 'controllerAction');
        } else {
            // Prüfen, ob eine ID angegeben wurde
            if ($id !== null) {
                // Überprüfen, ob die ID bereits existiert
                if (isset(self::$idRouteMap[$id])) {
                    throw new Exception("Fehler: Die Routen-ID $id wurde bereits verwendet.");
                }

                // Die Route zur ID hinzufügen
                self::$idRouteMap[$id] = [$pattern];
            }

            // Route hinzufügen
            self::$routes[] = compact('method', 'pattern', 'controllerAction');
        }
    }

    public static function getRoutesById($id, $paramsToRemove = [], $paramsToReplace = []) {
        // Überprüfen, ob eine Route für die angegebene ID existiert
        if (isset(self::$idRouteMap[$id])) {
            $routes = self::$idRouteMap[$id];

            // Durchlaufe die Routen
            foreach ($routes as $route) {
                // 1. Entferne Parameter, die nicht benötigt werden
                if (!empty($paramsToRemove)) {
                    foreach ($paramsToRemove as $param) {
                        // Entferne den Parameter in der Form /@param oder /@param[irgendwas]
                        $route = preg_replace('/\/@' . preg_quote($param, '/') . '(\[\d+(!)?\])?/', '', $route);
                    }
                }

                // 2. Ersetze Parameter, die ersetzt werden sollen
                if (!empty($paramsToReplace)) {
                    foreach ($paramsToReplace as $param => $replacement) {
                        // Ersetze @param oder @param[irgendwas] durch den Ersatzwert
                        $route = preg_replace('/@' . preg_quote($param, '/') . '(\[\d+(!)?\])?/', $replacement, $route);
                    }
                }

                // Rückgabe der ersten gefundenen (bearbeiteten) Route
                return $route;
            }
        }

        return null; // Keine Routen für diese ID gefunden
    }

    public static function reroute($url) {
        header("Location: $url");
        exit;
    }

    public static function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim($_SERVER['REQUEST_URI'], '/');

        foreach (self::$routes as $route) {
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

        self::handleError(404);
    }

    private static function handleError($statusCode) {
        foreach (self::$errorRoutes as $errorRoute) {
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
