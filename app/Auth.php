<?php

namespace App;

use \App\Models\Usuario;

class Auth
{

    /**
     * Verifica se esta logado
     * @return boolean
     */
    public function check()
    {
        return isset($_SESSION['usuario']);
    }

    /**
     * Retorna o usuário logado
     * @return Usuario
     */
    public function usuario()
    {
        if (empty($_SESSION['usuario'])) {
            return null;
        }
        
        return Usuario::find($_SESSION['usuario']);
    }

    /**
     * Verifica se o usuário que esta tentando logar é um usuário válido
     *
     * @param string $email
     * @param string $senha
     * @return boolean
     */
    public function login($email, $senha)
    {
        $usuario = Usuario::where("email", $email)->first();

        if (!$usuario) {
            return false;
        }

        if (password_verify($senha, $usuario->senha)) {
            $_SESSION['usuario'] = $usuario->id;
            return true;
        }

        return false;
    }

    /**
     * Destroi a sessão do usuário
     */
    public function logout()
    {
        unset($_SESSION['usuario']);
    }
}
