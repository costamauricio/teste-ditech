<?php

namespace App\Controllers;

use \App\Controllers\Controller;
use \App\Models\Usuario;

class IndexController extends Controller
{
    public function index($request, $response)
    {
        return $this->container->view->render($response, "index.twig");
    }
}
