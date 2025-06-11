<?php

namespace chaudiere\api\providers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class EventsLinksProvider implements EventsLinksProviderInterface
{
    public function generateEventLinks(array $events, ServerRequestInterface $request): array
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        for ($i = 0; $i < count($events); $i++) {
            $link = $routeParser->urlFor('api_events', ['id' => $events[$i]['id']]);
            $events[$i]['links'] = ['self' => ['href' => $link]];
        }

        return $events;
    }

    public function generateEventImageLinks(array $events, ServerRequestInterface $request): array
    {
        $twig = Twig::fromRequest($request);
        $globals = $twig->getEnvironment()->getGlobals();
        $img_dir = $globals['globals']['img_dir'];

        //On ajoute le lien de l'image
        $events['image'] = ['href' => $img_dir . '/' . $events['image']];
        return $events;
    }
}