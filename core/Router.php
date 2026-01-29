<?php
/**
 * Router Class
 * NGO Donor Management System
 */

class Router {
    private $routes = [];
    
    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }
    
    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }
    
    public function put($path, $handler) {
        $this->addRoute('PUT', $path, $handler);
    }
    
    public function delete($path, $handler) {
        $this->addRoute('DELETE', $path, $handler);
    }
    
    private function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
        ];
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove trailing slash
        $uri = rtrim($uri, '/');
        
        // Handle PUT and DELETE from forms
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            $params = $this->matchRoute($route['path'], $uri);
            if ($params !== false) {
                $this->callHandler($route['handler'], $params);
                return;
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        echo "404 - Page not found";
    }
    
    private function matchRoute($routePath, $uri) {
        $routeParts = explode('/', trim($routePath, '/'));
        $uriParts = explode('/', trim($uri, '/'));
        
        if (count($routeParts) !== count($uriParts)) {
            return false;
        }
        
        $params = [];
        
        foreach ($routeParts as $index => $part) {
            if (strpos($part, '{') === 0 && strpos($part, '}') === strlen($part) - 1) {
                // Parameter
                $paramName = trim($part, '{}');
                $params[$paramName] = $uriParts[$index];
            } elseif ($part !== $uriParts[$index]) {
                return false;
            }
        }
        
        return $params;
    }
    
    private function callHandler($handler, $params) {
        if (is_callable($handler)) {
            call_user_func_array($handler, array_values($params));
            return;
        }
        
        if (is_string($handler) && strpos($handler, '@') !== false) {
            list($controllerName, $method) = explode('@', $handler);
            $controllerName = 'controllers\\' . $controllerName;
            
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $method)) {
                    call_user_func_array([$controller, $method], array_values($params));
                    return;
                }
            }
        }
        
        throw new Exception("Handler not found: $handler");
    }
}
