<?php
declare(strict_types=1);
session_start();

use Slim\Factory\AppFactory;
use chaudiere\core\infrastructure\Eloquent;

Eloquent::init(__DIR__ . '/db.conf.ini.dist');

$app = AppFactory::create();
$app->addRoutingMiddleware(true, false, false);
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, false, false);

$app = (require_once __DIR__ . '/routes.php')($app);
return $app;