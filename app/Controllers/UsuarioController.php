<?php

namespace App\Controllers;

use \App\Controllers\Controller;
use \App\Models\Usuario;

class UsuarioController extends Controller
{
    public function remover($request, $response, $args)
    {
        if ($args['id'] == $this->container->auth->usuario()->id) {
            Usuario::find($args['id'])->delete();
            $this->container->flash->addMessage("success", "Usuário removido com sucesso.");
            return $response->withRedirect( $this->container->router->pathFor("auth.signout") );
        }

        return $response->withRedirect( $this->container->router->pathFor("index") );
    }
}
