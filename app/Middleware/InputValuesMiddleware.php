<?php

namespace App\Middleware;

use \App\Middleware\Middleware;

class InputValuesMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!empty($_SESSION['old'])) {
            $this->container->view->getEnvironment()->addGlobal("old", $_SESSION['old']);
        }

        $_SESSION['old'] = $request->getParams();
        
        return $next($request, $response);
    }
}
