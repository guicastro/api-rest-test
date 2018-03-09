<?php

namespace Classes;

abstract class Bootstrap
{
    protected $routes;

    public function __construct() {

        $this->initRoutes();
        $this->run($this->getUrl());

    }

    abstract protected function initRoutes();

    protected function run($url) {

        array_walk($this->routes,function($route) use ($url){

            if($url=="/php_oo_mvc/course_mvc/public".$route['route']) {

                $class = "App\\Controllers\\".ucfirst($route['controller']);
                $controller = new $class;
                $action = $route['action'];
                $controller->$action();
            }
        });
    }

    protected function setRoutes(array $routes) {

        $this->routes = $routes;
    }
    protected function getUrl() {

        return parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
    }

}