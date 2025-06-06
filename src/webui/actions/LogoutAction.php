<?php

namespace chaudiere\webui\actions;

use chaudiere\webui\exceptions\ProviderAuthentificationException;
use chaudiere\webui\providers\AuthProviderInterface;
use chaudiere\webui\providers\SessionAuthProvider;
use Slim\Flash\Messages;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class LogoutAction
{
    private AuthProviderInterface $authProvider;

    public function __construct()
    {
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke($request, $response, $args)
    {
        $this->authProvider->logout();

        $flash = new Messages();
        $flash->addMessage('info', "Deconnexion réussie !");

        // Rediriger vers la page de connexion
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
    }
}