<?php
declare(strict_types=1);
session_start();

use chaudiere\webui\middleware\TwigGlobalSnackBarMiddleware;
use chaudiere\webui\middleware\TwigGlobalUserMiddleware;
use Slim\Factory\AppFactory;
use chaudiere\infrastructure\Eloquent;
use Slim\Flash\Messages;
use Slim\Views\TwigMiddleware;

Eloquent::init(__DIR__ . '/db.conf.ini.dist');

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, false, false);
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
        ->withHeader('Access-Control-Allow-Credentials', 'true');
});


$twig = \Slim\Views\Twig::create(__DIR__ . '/../webui/views', ['cache' => false, 'auto_reload' => true , 'strict_variables' => true]);
$twig->getEnvironment()
    ->addGlobal('globals', [
            'css_dir'=> '/static/css/',
            'img_dir'=> '/static/images/',
        ]
    );

$flash = new Messages();
$twig->getEnvironment()->addGlobal('flash', $flash);

$app->add(new TwigGlobalUserMiddleware($twig));
$app->add(new TwigGlobalSnackBarMiddleware());
$app->add(TwigMiddleware::create($app, $twig));

$app = (require_once __DIR__ . '/routes.php')($app);
return $app;