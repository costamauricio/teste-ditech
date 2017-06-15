<?php

namespace App\Controllers;

use \App\Controllers\Controler;
use \App\Models\Usuario;
use \Respect\Validation\Validator;

class AuthController extends Controller
{
    /**
     * Renderiza a tela de registro de novos usuários
     */
    public function signUp($request, $response)
    {
        return $this->container->view->render($response, "auth/signup.twig");
    }

    /**
     * Cadastra um novo usuário
     */
    public function postSignUp($request, $response)
    {
        $validacao = $this->container->validator->validate($request, [
            'nome' => validator::notEmpty()->alpha()->notBlank(),
            'email' => validator::noWhitespace()->notEmpty()->email(),
            'senha' => validator::notBlank()->notEmpty()->length(6)
        ]);

        if ($validacao->failed()) {
            return $response->withRedirect( $this->container->router->pathFor("auth.signup") );
        }

        $usuario = new Usuario();

        $usuario->nome = $request->getParam("nome");
        $usuario->email = $request->getParam("email");
        $usuario->senha = password_hash($request->getParam("senha"), PASSWORD_DEFAULT);

        $usuario->save();

        $this->container->auth->login($usuario->email, $request->getParam('senha'));

        return $response->withRedirect( $this->container->router->pathFor("index") );
    }

    /**
     * Renderiza a tela de login
     */
    public function signIn($request, $response)
    {
        return $this->container->view->render($response, "auth/signin.twig");
    }

    /**
     * Loga o usuário
     */
    public function postSignIn($request, $response)
    {
        $login = $this->container->auth->login(
            $request->getParam('email'),
            $request->getParam('senha')
        );

        if (!$login) {
            return $response->withRedirect( $this->container->router->pathFor("auth.signin") );
        }

        return $response->withRedirect( $this->container->router->pathFor("index") );
    }

    /**
     * Desloga o usuário
     */
    public function signOut($request, $response)
    {
        $this->container->auth->logout();

        return $response->withRedirect( $this->container->router->pathFor("index") );
    }
}
