<?php
use chaudiere\api\actions\CategoriesAction;
use chaudiere\api\actions\EvenementsAction;
use chaudiere\api\actions\EventsByCategorieAction;
use chaudiere\webui\actions\CreationEventAction;
use chaudiere\webui\actions\CreationCategorieAction;
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
    $app->get('/api/events[/[{id}[/]]]', EvenementsAction::class)->setName('api_events');

    // API de toutes les catégories avec option d'en chercher une par id
    $app->get('/api/categories/{id}/events[/]', EventsByCategorieAction::class)->setName('api_events_by_categories');

    $app->map(['GET', 'POST'], '/createEvent', CreationEventAction::class)->setName('createEvent');

    $app->map(['GET', 'POST'], '/createCategorie', CreationCategorieAction::class)->setName('createCategorie');

    return $app;
};