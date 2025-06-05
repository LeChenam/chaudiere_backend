<?php

use chaudiere\webui\actions\CreationEventAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (Slim\App $app) {

    $app->get('[/]', function (Request $request, Response $response) {
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, 'pages/ViewAccueil.twig');
    })->setName('home');

    $app->map(['GET', 'POST'], '/createEvent', CreationEventAction::class)->setName('createEvent');

    return $app;
};