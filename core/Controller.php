<?php

namespace core;

use app\exceptions\ControllerNotExistsException;
use app\classes\Uri;

class Controller
{
    private string $uri;
    private string $namespace;
    private string $controller;
    private $folders = [
        'app\controllers\portal',
        'app\controllers\admin'
    ];

    public function __construct()
    {
        $this->uri = Uri::uri();
    }

    public function load()
    {
        if ($this->isHome()) {
            return $this->controllerHome();
        }

        return $this->controllerNotFound();
    }

    private function controllerHome()
    {
        if (!$this->controllerExists('HomeController')) {
            throw new ControllerNotExistsException('Essa página não existe');
        }

        return $this->instantiateController();
    }

    private function controllerNotFound()
    {
        $controller = $this->getControllerNotFound();

        if (!$this->controllerExists($controller)) {
            throw new ControllerNotExistsException('Essa página não existe');
        }

        return $this->instantiateController();
    }

    private function getControllerNotFound()
    {
        if (substr_count($this->uri, '/') > 1) {
            list($this->controller) = array_values(array_filter(explode('/', $this->uri)));
            return ucfirst($this->controller) . 'Controller';
        }

        return ucfirst(ltrim($this->uri, '/')) . 'Controller';
    }

    private function isHome()
    {
        return $this->uri === '/';
    }

    private function controllerExists(string $controller): bool
    {
        $controllerExist = false;

        foreach ($this->folders as $folder) {
            if (class_exists($folder . '\\' . $controller)) {
                $controllerExist = true;
                $this->namespace = $folder;
                $this->controller = $controller;
            }
        }

        return $controllerExist;
    }

    private function instantiateController()
    {
        $controller = $this->namespace . '\\' . $this->controller;
        return new $controller;
    }

}
