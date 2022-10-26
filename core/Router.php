<?php

namespace Core;

use Config\Route;
use Exception;

/**
 * Router
 */
class Router
{

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var array
     */
    protected $route = [];

    /**
     * Router constructor
     */
    public function __construct() {

        $routes = Route::$urls;

        foreach ($routes as $controller => $actions) {
            foreach ($actions as $name => $value) {
                $url = $value['url'];
                $url = preg_replace('/\//', '\\/', $url);
                $url = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[0-9-]+)', $url);
                $url = '/^' . $url . '$/i';

                $this->routes[] = [
                    'url' => $url,
                    'controller' => $controller,
                    'action' => $name,
                    'method' => $value['method']
                ];
            }
        }
    }

    /**
     * @return array
     */
    public function getRoutes(): array {
        return $this->routes;
    }

    /**
     * @return array
     */
    public function getRoute(): array {
        return $this->route;
    }

    /**
     * @param $method
     * @param $url
     * @return bool
     */
    public function match($method, $url): bool {
        $params = [];
        $body = [];
        foreach ($this->routes as $route) {
            if (preg_match($route['url'], $url, $matches) && strtoupper($route['method']) === $method) {

                foreach ($matches as $key => $match)
                    if (is_string($key))
                        $params[$key] = $match;

                if ($method == 'POST' || 'PATCH')
                    $body = (json_decode(file_get_contents('php://input'), true)) ? : [];

                $this->route['controller'] = $route['controller'];
                $this->route['action'] = $route['action'];
                $this->route['data'] = [
                    'params' => $params,
                    'body' => $body
                ] ;

                return true;
            }
        }

        return false;
    }

    /**
     * @param $method
     * @param $url
     * @return void
     * @throws Exception
     */
    public function dispatch($method, $url) {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($method, $url)) {
            $controller = $this->route['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller . 'Controller';

            if (class_exists($controller)) {
                $controller_object = new $controller($this->route['data']);

                $action = $this->route['action'];
                $action = $this->convertToCamelCase($action);

                if (preg_match('/action$/i', $action) == 0) {
                    $controller_object->$action();

                } else {
                    throw new Exception("Method $action in controller $controller cannot be called directly - remove the Action suffix to call this method");
                }
            } else {
                throw new Exception("Controller class $controller not found");
            }
        } else {
            throw new Exception('No route matched.', 404);
        }
    }

    /**
     * @param $string
     * @return string
     */
    protected function convertToStudlyCaps($string): string {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * @param $string
     * @return string
     */
    protected function convertToCamelCase($string): string {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * @param $url
     * @return mixed|string
     */
    protected function removeQueryStringVariables($url) {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }

    /**
     * @return string
     */
    protected function getNamespace(): string {
        $namespace = 'Api\Controllers\\';

        if (array_key_exists('namespace', $this->route)) {
            $namespace .= $this->route['namespace'] . '\\';
        }

        return $namespace;
    }
}
