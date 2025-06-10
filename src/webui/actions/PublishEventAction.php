<?php

namespace chaudiere\webui\actions;

use chaudiere\core\application\authorization\AuthorizationService;
use chaudiere\core\application\authorization\AuthorizationServiceInterface;
use chaudiere\core\application\exceptions\AuthentificationException;
use chaudiere\core\application\exceptions\AuthorizationException;
use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\application\usecases\EventManagement;
use chaudiere\core\application\usecases\EventManagementInterface;
use chaudiere\core\domain\exceptions\EntityNotFoundException;
use chaudiere\webui\exceptions\CsrfException;
use chaudiere\webui\exceptions\ProviderAuthentificationException;
use chaudiere\webui\providers\AuthProviderInterface;
use chaudiere\webui\providers\CsrfTokenProviderInterface;
use chaudiere\webui\providers\SessionAuthProvider;
use chaudiere\webui\providers\SessionCsrfTokenProvider;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Flash\Messages;
use Slim\Routing\RouteContext;

class PublishEventAction
{

    private AuthProviderInterface $authProvider;
    private AuthorizationServiceInterface $authorizationService;
    private EventManagementInterface $eventManagement;
    private CsrfTokenProviderInterface $csrfTokenProvider;

    public function __construct()
    {
        $this->authProvider = new SessionAuthProvider();
        $this->eventManagement = new EventManagement();
        $this->authorizationService = new AuthorizationService();
        $this->csrfTokenProvider = new SessionCsrfTokenProvider();
    }

    public function __invoke($request, $response, array $args)
    {
        try {
            $user = $this->authProvider->getSignedInUser();
        } catch (ProviderAuthentificationException $e) {

            $flash = new Messages();
            $flash->addMessage('error', "Vous devez être connecté pour accéder à cette page.");

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
        }

        // Vérifier les permissions de l'utilisateur
        try {
            if (!$this->authorizationService->isAuthorized($user['id'], AuthorizationServiceInterface::PERMISSION_UPDATE_EVENT)) {
                $flash = new Messages();
                $flash->addMessage('error', "Vous n'avez pas la permission de publier des événements.");
                $routeParser = RouteContext::fromRequest($request)->getRouteParser();
                return $response->withHeader('Location', $routeParser->urlFor('events'))->withStatus(403);
            }
        } catch (AuthentificationException $e) {
            throw new HttpUnauthorizedException($request, "Authentification requise : " . $e->getMessage());
        } catch (AuthorizationException $e) {
            throw new HttpForbiddenException($request, $e->getMessage());
        }

        // Vérifier le token CSRF
        $csrfToken = $request->getParsedBody()['csrf_token'] ?? '';
        try {
            $this->csrfTokenProvider->checkCsrf($csrfToken);
        } catch (CsrfException $e) {
            throw new HttpBadRequestException($request, "Token CSRF invalide : " . $e->getMessage());
        }

        $eventId = $request->getParsedBody()['event_id'] ?? null;
        try {
            $this->eventManagement->publishEvent($eventId);
        } catch (ExceptionInterne $e) {
            throw new HttpInternalServerErrorException($request, "Erreur interne lors de la publication de l'événement : " . $e->getMessage());
        } catch (EntityNotFoundException $e) {
            throw new HttpNotFoundException($request, "Événement introuvable : " . $e->getMessage());
        }

        $flash = new Messages();
        $flash->addMessage('success', "Événement publié avec succès.");
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        return $response->withHeader('Location', $routeParser->urlFor('events'))->withStatus(302);
    }

}