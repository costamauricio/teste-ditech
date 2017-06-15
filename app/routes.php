<?php

use \App\Middleware\AuthMiddleware;

$app->get("/auth/signup", "AuthController:signUp")->setName("auth.signup");
$app->post("/auth/signup", "AuthController:postSignUp");

$app->get("/auth/signin", "AuthController:signIn")->setName("auth.signin");
$app->post("/auth/signin", "AuthController:postSignIn");

$app->group('', function() {
    $this->get('/', "IndexController:index")->setName('index');
    $this->get("/auth/signout", "AuthController:signOut")->setName("auth.signout");
})->add(new AuthMiddleware($container));
