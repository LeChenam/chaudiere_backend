<?php

namespace chaudiere\webui\actions;

use chaudiere\core\application\authorization\AuthorizationService;
use chaudiere\core\application\authorization\AuthorizationServiceInterface;
use chaudiere\core\application\exceptions\AuthentificationException;
use chaudiere\core\application\exceptions\AuthorizationException;
use chaudiere\webui\exceptions\CsrfException;
use chaudiere\webui\exceptions\ProviderAuthentificationException;
use chaudiere\webui\providers\AuthProviderInterface;
use chaudiere\webui\providers\CsrfTokenProviderInterface;
use chaudiere\webui\providers\SessionAuthProvider;
use chaudiere\webui\providers\SessionCsrfTokenProvider;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;

class HomePageAction
{
    private string $template;
    private AuthProviderInterface $authProvider;

    public function __construct()
    {
        $this->template = 'pages/ViewAccueil.twig';
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke($request, $response, array $args)
    {

        // Vérifier si l'utilisateur est authentifié
        try {
            $this->authProvider->getSignedInUser();
        } catch (ProviderAuthentificationException $e) {
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
        }

        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, $this->template);
    }

}