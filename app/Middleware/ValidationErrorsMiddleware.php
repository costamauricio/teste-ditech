<?php

namespace App\Middleware;

use \App\Middleware\Middleware;

class ValidationErrorsMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!empty($_SESSION["erros"])) {
            $this->container->view->getEnvironment()->addGlobal("erros", $_SESSION["erros"]);
            unset($_SESSION["erros"]);
        }

        return $next($request, $response);
    }
}
