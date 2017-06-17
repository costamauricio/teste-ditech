<?php

namespace App\Controllers;

use \App\Controllers\Controller;
use \App\Models\Sala;
use \Respect\Validation\Validator;

class SalaController extends Controller
{
    public function getSalas($request, $response)
    {
        return $response->withJson(Sala::all());
    }

    public function editar($request, $response, $args)
    {
        $sala = null;

        if (!empty($args['id'])) {
            $sala = Sala::find($args['id'])->toArray();
        }

        return $this->container->view->render($response, "sala/editar.twig", ["sala" => $sala]);
    }

    public function postEditar($request, $response)
    {
        $validacao = $this->container->validator->validate($request, [
            'descricao' => validator::notEmpty()->notBlank()
        ]);

        if ($validacao->failed()) {
            return $response->withRedirect( $this->container->router->pathFor("salas.nova") );
        }

        if ($request->getParam('id')) {
            $sala = Sala::find($request->getParam('id'));
        } else {
            $sala = new Sala();
        }

        $sala->descricao = $request->getParam("descricao");
        $sala->save();

        $this->container->flash->addMessage("success", "Sala salva com sucesso.");
        return $response->withRedirect( $this->container->router->pathFor("salas.listar") );
    }

    public function remover($request, $response, $args)
    {
        $sala = Sala::find($args['id']);

        if (!$sala) {
            $this->container->flash->addMessage("error", "Sala não encontrada.");
        } else if (!$sala->delete()) {
            $this->container->flash->addMessage("error", "Erro ao remover a sala.");
        } else {
            $this->container->flash->addMessage("success", "Sala removida com sucesso.");
        }

        return $response->withRedirect( $this->container->router->pathFor("salas.listar") );
    }


    public function listar($request, $response)
    {
        $salas = Sala::all();
        return $this->container->view->render($response, "sala/listar.twig", ["salas" => $salas->toArray()]);
    }
}
