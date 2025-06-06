<?php

namespace chaudiere\webui\actions;

use chaudiere\webui\exceptions\CsrfException;
use chaudiere\webui\exceptions\ProviderAuthentificationException;
use chaudiere\webui\providers\AuthProviderInterface;
use chaudiere\webui\providers\CsrfTokenProviderInterface;
use chaudiere\webui\providers\SessionAuthProvider;
use chaudiere\webui\providers\SessionCsrfTokenProvider;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Flash\Messages;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class LoginAction
{

    private string $template;
    private AuthProviderInterface $authProvider;
    private CsrfTokenProviderInterface $csrfTokenProvider;

    public function __construct()
    {
        $this->template = 'pages/ViewLogin.twig';
        $this->authProvider = new SessionAuthProvider();
        $this->csrfTokenProvider = new SessionCsrfTokenProvider();
    }

    public function __invoke($request, $response, $args)
    {
        if ($request->getMethod() === 'POST') {

            $params = $request->getParsedBody();
            $email = $params['email'] ?? '';
            $password = $params['password'] ?? '';
            $csrfToken = $params['csrf_token'] ?? '';

            // Vérifier le token CSRF
            try {
                $this->csrfTokenProvider->checkCsrf($csrfToken);
            } catch (CsrfException $e) {
                throw new HttpBadRequestException($request, "Token CSRF invalide.");
            }

            // Authentifier l'utilisateur
            try {
                $this->authProvider->loginByCredential($email, $password);
            } catch (ProviderAuthentificationException $e) {
                throw new HttpUnauthorizedException($request,"Authentification échouée : " . $e->getMessage());
            }

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            try {
                $this->authProvider->getSignedInUser();

                $flash = new Messages();
                $flash->addMessage('info', "Connexion réussie !");

                return $response->withHeader('Location', $routeParser->urlFor('home'))->withStatus(302);
            } catch (ProviderAuthentificationException $e) {
                throw new HttpInternalServerErrorException($request, $e->getMessage());
            }

        } else {
            $token = $this->csrfTokenProvider->generateCsrf();

            // Afficher le formulaire
            $view = Twig::fromRequest($request);
            return $view->render($response, $this->template , [
                'csrf_token' => $token
            ]);
        }
    }
}