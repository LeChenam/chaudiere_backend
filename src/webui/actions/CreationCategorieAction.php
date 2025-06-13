<?php
namespace chaudiere\webui\actions;

use chaudiere\core\application\usecases\CategorieManagement;
use chaudiere\webui\exceptions\ProviderAuthentificationException;
use chaudiere\webui\providers\AuthProviderInterface;
use chaudiere\webui\providers\SessionAuthProvider;
use chaudiere\webui\providers\SessionCsrfTokenProvider;
use chaudiere\webui\providers\CsrfTokenProviderInterface;
use chaudiere\webui\exceptions\CsrfException;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Flash\Messages;
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

            try {
                $csrfToken = $this->csrfTokenProvider->generateCsrf();
            } catch (CsrfException $e) {
                throw new HttpInternalServerErrorException($request, "Erreur lors de la génération du token CSRF : " . $e->getMessage());
            }
            return $view->render($response, $this->template, ['csrf_token' => $csrfToken]);
        } else {
            $queryParam = $request->getParsedBody();
            $csrfToken = $queryParam['csrf_token'] ?? null;

            try {
                $this->csrfTokenProvider->checkCsrf($csrfToken);
            } catch (CsrfException $e) {
                throw new HttpBadRequestException($request, "Token CSRF invalide.");
            }

            try{
                $nom = $queryParam['libelle'] ?? '';
                $description = $queryParam['description'] ?? '';
                if (empty($nom) || empty($description)) {
                    throw new InvalidArgumentException("Le nom et la description sont obligatoires.");
                }
                if (FILTER_VAR($nom, FILTER_SANITIZE_SPECIAL_CHARS) === false || FILTER_VAR($description, FILTER_SANITIZE_SPECIAL_CHARS) === false) {
                    throw new InvalidArgumentException("Nom ou description invalide.");
                }
                $this->categorieManagement->createCategorie($nom, $description);
            } catch (\Exception $e) {
                throw new HttpBadRequestException($request, $e);
            }

            $flash = new Messages();
            $flash->addMessage('success', 'La catégorie a été créée avec succès.');

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location', $routeParser->urlFor('createCategorie'))->withStatus(302);
        }
    }
}

