<?php
use chaudiere\api\actions\CategoriesAction;
use chaudiere\api\actions\EvenementsAction;
use chaudiere\api\actions\EventsByCategorieAction;
use chaudiere\api\actions\EventsByPeriodeAction;
use chaudiere\webui\actions\CreationEventAction;
use chaudiere\webui\actions\CreationCategorieAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (Slim\App $app) {

    $app->get('[/]', function (Request $request, Response $response) {
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, 'pages/ViewAccueil.twig');
    })->setName('home');

    // API de toutes les catégories avec option d'en chercher une par id
    $app->get('/api/categories[/[{id}[/]]]', CategoriesAction::class)->setName('api_categories');

    // API de tous les événements avec option d'en chercher un par id
    $app->get('/api/evenements[/[{id}[/]]]', EvenementsAction::class)->setName('api_events');

    // API de toutes les catégories avec option d'en chercher une par id
    $app->get('/api/categories/{id}/evenements[/]', EventsByCategorieAction::class)->setName('api_events_by_categories');

    $app->map(['GET', 'POST'], '/createEvent', CreationEventAction::class)->setName('createEvent');

    $app->map(['GET', 'POST'], '/createCategorie', CreationCategorieAction::class)->setName('createCategorie');

    return $app;
};