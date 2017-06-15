<?php

require __DIR__ . "/../vendor/autoload.php";

session_start();

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver' => "mysql",
            'host' => "127.0.0.1",
            'port' => 3306,
            'database' => "agenda",
            'username' => "root",
            'password' => "root",
            'charset' => "utf8",
            'collation' => "utf8_unicode_ci",
        ]
    ],
]);

$container = $app->getContainer();

/**
 * Configuração do Eloquent
 */
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($container) use($capsule) {
    return $capsule;
};

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

    $view->getEnvironment()->addGlobal("auth", [
        'check' => $container->auth->check(),
        'usuario' => $container->auth->usuario()
    ]);

    return $view;
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

/**
 * Carregamento das rotas
 */
require __DIR__ . "/../app/routes.php";
