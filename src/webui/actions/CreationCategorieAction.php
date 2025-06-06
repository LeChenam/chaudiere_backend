<?php
namespace chaudiere\webui\actions;

use chaudiere\core\domain\entities\Categorie;
use chaudiere\core\application\usecases\CategorieManagement;
use chaudiere\webui\exceptions\ProviderAuthentificationException;
use chaudiere\webui\providers\AuthProviderInterface;
use chaudiere\webui\providers\SessionAuthProvider;
use chaudiere\webui\providers\SessionCsrfTokenProvider;
use chaudiere\webui\providers\CsrfTokenProviderInterface;
use chaudiere\webui\exceptions\CsrfException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpBadRequestException;
use Exception;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class CreationCategorieAction{
    private string $template;
    private CategorieManagement $categorieManagement;
    private AuthProviderInterface $authProvider;
    private CsrfTokenProviderInterface $csrfTokenProvider;

    public function __construct(){
        $this->template = 'pages/ViewCreationCategorie.twig';
        $this->categorieManagement = new CategorieManagement();
        $this->authProvider = new SessionAuthProvider();
        $this->csrfTokenProvider = new SessionCsrfTokenProvider();
    }

    public function __invoke(Request $request,Response $response,array $args): Response
    {
        if($request->getMethod() == 'GET') {

            try {
                $this->authProvider->getSignedInUser();
            } catch (ProviderAuthentificationException $e) {
                throw new HttpUnauthorizedException($request, "Vous devez être connecté pour accéder à cette page.");
            }

            $view = Twig::fromRequest($request);
            $csrfToken = $this->csrfTokenProvider->generateCsrf();
            return $view->render($response, $this->template, ['csrf_token' => $csrfToken]);
        } elseif ($request->getMethod() == 'POST') {
            $queryParam = $request->getParsedBody();
            $csrfToken = $queryParam['csrf_token'] ?? null;

            try {
                $this->csrfTokenProvider->checkCsrf($csrfToken);
            } catch (CsrfException $e) {
                throw new HttpBadRequestException($request, "Token CSRF invalide.");
            }

            try{
                $nom = $queryParam['nom'] ?? '';
                $description = $queryParam['description'] ?? '';
                if (empty($nom) || empty($description)) {
                    throw new InvalidArgumentException("Le nom et la description sont obligatoires.");
                }
                $this->categorieManagement->createCategorie($nom, $description);
            } catch (Exception $e) {
                return $response->withStatus(500)->write('Error: ' . $e->getMessage());
            }

            $request = $request->withAttribute('flash_message', 'Box créée avec succès !');

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location', $routeParser->urlFor('home'))->withStatus(302);
        } else {
            return $response->withStatus(405);
        }
    }
}

