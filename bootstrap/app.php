<?php

require __DIR__ . "/../vendor/autoload.php";

session_start();

$config = parse_ini_file(__DIR__ . "/../config.ini");

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => $config["development"],
        'db' => [
            'driver' => "mysql",
            'host' => $config["host"],
            'port' => $config["port"],
            'database' => $config["database"],
            'username' => $config["username"],
            'password' => $config["password"],
            'charset' => "utf8",
            'collation' => "utf8_unicode_ci",
        ]
    ]
]);

$container = $app->getContainer();

/**
 * Configuração do Eloquent
 */
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['auth'] = function($container) {
    return new \App\Auth();
};

/**
 * Configuração do Twig
 */
$container['view'] = function($container) {
    $view = new \Slim\Views\Twig( __DIR__ . "/../resources/views", [
        'cache' => false
    ]);

    $view->addExtension(
        new \Slim\Views\TwigExtension(
            $container->router,
            $container->request->getUri()
        )
    );

    /**
     * Seta o controle do usuário logado para acesso nas views
     */
    $view->getEnvironment()->addGlobal("auth", [
        'check' => $container->auth->check(),
        'usuario' => $container->auth->usuario()
    ]);

    /**
     * Seta as mensagens
     */
    $view->addExtension(new \Knlv\Slim\Views\TwigMessages(
        new \Slim\Flash\Messages()
    ));

    return $view;
};

/**
 * Registra os providers
 */
$container['validator'] = function($container) {
    return new \App\Validator();
};

$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

/**
 * Declaração dos controllers
 */
$container['IndexController'] = function($container) {
    return new \App\Controllers\IndexController($container);
};

$container['AuthController'] = function($container) {
    return new \App\Controllers\AuthController($container);
};

$container['SalaController'] = function($container) {
    return new \App\Controllers\SalaController($container);
};

$container['ReservaController'] = function($container) {
    return new \App\Controllers\ReservaController($container);
};

$container['UsuarioController'] = function($container) {
    return new \App\Controllers\UsuarioController($container);
};

/**
 * Define os middlewares
 */
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\InputValuesMiddleware($container));

/**
 * Carregamento das rotas
 */
require __DIR__ . "/../app/routes.php";
