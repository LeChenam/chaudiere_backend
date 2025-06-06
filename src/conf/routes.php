<?php
use chaudiere\api\actions\CategoriesAction;
use chaudiere\api\actions\EvenementsAction;
use chaudiere\api\actions\EventsByCategorieAction;
use chaudiere\api\actions\EventsByPeriodeAction;
use chaudiere\webui\actions\CreationEventAction;
use chaudiere\webui\actions\CreationCategorieAction;
use chaudiere\webui\actions\GetEventAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use chaudiere\webui\actions\HomePageAction;
use chaudiere\webui\actions\LoginAction;
use chaudiere\webui\actions\LogoutAction;
use chaudiere\webui\actions\RegisterAction;

return function (Slim\App $app) {

    $app->get('[/]',  HomePageAction::class)->setName('home');

    // Authentication
    $app->map(['GET', 'POST'], '/login[/]', LoginAction::class)->setName('login');
    $app->map(['GET', 'POST'], '/register[/]', RegisterAction::class)->setName('register');
    $app->post('/logout[/]',  LogoutAction::class)->setName('logout');

    // API de toutes les catégories avec option d'en chercher une par id
    $app->get('/api/categories[/[{id}[/]]]', CategoriesAction::class)->setName('api_categories');

    // API de tous les événements avec option d'en chercher un par id
    $app->get('/api/evenements[/[{id}[/]]]', EvenementsAction::class)->setName('api_events');

    // API de toutes les catégories avec option d'en chercher une par id
    $app->get('/api/categories/{id}/evenements[/]', EventsByCategorieAction::class)->setName('api_events_by_categories');

    $app->get('/events[/]', GetEventAction::class)->setName('events');

    $app->map(['GET', 'POST'], '/createEvent', CreationEventAction::class)->setName('createEvent');

    $app->map(['GET', 'POST'], '/createCategorie', CreationCategorieAction::class)->setName('createCategorie');

    return $app;
};