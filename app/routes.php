<?php

use \Slim\Http\Request;
use \Slim\Http\Response;

$app->get('/', function(Request $request, Response $response) {
    return $this->view->render($response, "index.twig");
});
