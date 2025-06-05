<?php
declare(strict_types=1);
session_start();

use Slim\Factory\AppFactory;
use chaudiere\infrastructure\Eloquent;

Eloquent::init(__DIR__ . '/db.conf.ini.dist');

$app = AppFactory::create();
$app->addRoutingMiddleware(true, false, false);
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, false, false);

$twig = \Slim\Views\Twig::create(__DIR__ . '/../webui/views', ['cache' => false, 'auto_reload' => true , 'strict_variables' => true]);
$twig->getEnvironment()
    ->addGlobal('globals', [
            'css_dir'=> 'static/css',
            'img_dir'=> 'static/img',
        ]
    );

$app->add(\Slim\Views\TwigMiddleware::create($app, $twig));

$app = (require_once __DIR__ . '/routes.php')($app);
return $app;