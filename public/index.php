<?php

use core\Controller;
use core\Method;
use core\Parameters;

require '../bootstrap.php';

// https://localhost.com.br/funcionario/show/123
// /funcionario/show/123 = URI

//dd(app\classes\Uri::uri());

try {
    $controller = new Controller;
    $controller = $controller->load();

    $method = new Method;
    $method = $method->load($controller);

    $parameters = new Parameters();
    $parameters = $parameters->load();

    $controller->$method($parameters);

} catch (\Exception $e) {
    dd($e->getMessage());
}

//
//$controller->$method($parameters);
