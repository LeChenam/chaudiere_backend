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

class RegisterAction
{
    private string $template;
    private AuthProviderInterface $authProvider;
    private AuthorizationServiceInterface $authorizationService;
    private CsrfTokenProviderInterface $tokenProvider;

    public function __construct()
    {
        $this->template = 'pages/ViewRegister.twig';
        $this->authProvider = new SessionAuthProvider();
        $this->authorizationService = new AuthorizationService();
        $this->tokenProvider = new SessionCsrfTokenProvider();
    }

    public function __invoke($request, $response, array $args)
    {
        if ($request->getMethod() === 'POST') {
            // Traiter le formulaire
            $params = $request->getParsedBody();
            $email = $params['email'] ?? '';
            $password = $params['password'] ?? '';
            $csrfToken = $params['csrf_token'] ?? '';

            // Vérifier le token CSRF
            try {
                $this->tokenProvider->checkCsrf($csrfToken);
            } catch (CsrfException $e) {
                throw new HttpBadRequestException($request, "Token CSRF invalide.");
            }

            // Valider les données
            if(FILTER_VAR($email, FILTER_VALIDATE_EMAIL) === false || empty($email)) {
                throw new HttpBadRequestException($request, "Adresse e-mail invalide.");
            }

            if (empty($password) || strlen($password) < 6 || !preg_match('/[a-zA-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
                throw new HttpBadRequestException($request, "Le mot de passe doit contenir au moins 6 caractères et inclure des lettres et des chiffres.");
            }

            $this->verifAuthz($request);

            // Authentifier l'utilisateur
            try {
                $this->authProvider->register($email, $password);
            } catch (ProviderAuthentificationException $e) {
                throw new HttpUnauthorizedException($request,"Authentification échouée : " . $e->getMessage());
            }

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location', $routeParser->urlFor('home'))->withStatus(302);
        } else {

            $this->verifAuthz($request);

            // Générer un token CSRF
            $token = $this->tokenProvider->generateCsrf();

            // Afficher le formulaire d'inscription
            $view = \Slim\Views\Twig::fromRequest($request);
            return $view->render($response, $this->template , [
                'csrf_token' => $token
            ]);
        }
    }

    private function verifAuthz($request): void
    {
        try {
            $user = $this->authProvider->getSignedInUser();
        } catch (ProviderAuthentificationException $e) {
            throw new HttpUnauthorizedException($request, "Vous devez être connecté inscrire un compte.");
        }

        try {
            $bool = $this->authorizationService->isAuthorized($user['id'], AuthorizationServiceInterface::PERMISSION_CREATE_USER);
        } catch (AuthentificationException $e) {
            throw new HttpUnauthorizedException($request, "Authentification échouée : " . $e->getMessage());
        } catch (AuthorizationException $e) {
            throw new HttpForbiddenException($request, "Action non autorisée : " . $e->getMessage());
        }

        if (!$bool) {
            throw new HttpForbiddenException($request, "Vous n'avez pas la permission de créer un compte utilisateur.");
        }
    }
}