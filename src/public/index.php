<?php

session_start();
require __DIR__ . '/../../vendor/autoload.php';
use Slim\Http\UploadedFile;

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App([
  'settings' => [
    'displayErrorDetails' => true,
  ]
]);

$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../views', [  //select the direcory where the views are kept
        'cache' => false, //turn on during the production stage
    ]);

    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER)); //$uri = $container->request->getUri();
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    $assetManager = new LoveCoding\TwigAsset\TwigAssetManagement([
        'verion' => '1'
    ]);
    $assetManager->addPath('css', '/css');
    $assetManager->addPath('img', '/img');
    $assetManager->addPath('js', '/js');
    $view->addExtension($assetManager->getAssetExtension());

    return $view;
};



 require __DIR__ . '/../routes/routes.php';


$app->run();
