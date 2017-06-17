<?php

use \App\Middleware\AuthMiddleware;

$app->get("/auth/signup", "AuthController:signUp")->setName("auth.signup");
$app->post("/auth/signup", "AuthController:postSignUp");

$app->get("/auth/signin", "AuthController:signIn")->setName("auth.signin");
$app->post("/auth/signin", "AuthController:postSignIn");

$app->group('', function() {

    $this->get("/auth/signout", "AuthController:signOut")->setName("auth.signout");

    $this->get("/", "IndexController:index")->setName('index');
    $this->get("/salas", "SalaController:getSalas");
    $this->get("/salas/listar", "SalaController:listar")->setName("salas.listar");
    $this->get("/salas/editar", "SalaController:editar")->setName("salas.nova");
    $this->post("/salas/editar", "SalaController:postEditar");
    $this->get("/salas/editar/{id}", "SalaController:editar")->setName("salas.editar");
    $this->get("/salas/remover/{id}", "SalaController:remover")->setName("salas.remover");
    $this->get("/reservas", "ReservaController:getReservas");
    $this->post("/reservas/reservar", "ReservaController:reservar");
    $this->get("/reservas/detalhes", "ReservaController:detalhes");
    $this->get("/reservas/remover", "ReservaController:remover");
})->add(new AuthMiddleware($container));
