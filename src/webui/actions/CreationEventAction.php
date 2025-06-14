<?php

namespace chaudiere\webui\actions;
use chaudiere\core\application\exceptions\EntityNotFoundException;
use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\application\usecases\CategorieManagement;
use chaudiere\core\application\usecases\EventManagement;
use chaudiere\webui\exceptions\CsrfException;
use chaudiere\webui\exceptions\ProviderAuthentificationException;
use chaudiere\webui\providers\AuthProviderInterface;
use chaudiere\webui\providers\CsrfTokenProviderInterface;
use chaudiere\webui\providers\SessionAuthProvider;
use chaudiere\webui\providers\SessionCsrfTokenProvider;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Flash\Messages;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class CreationEventAction{
    private string $template;
    private EventManagement $event;
    private CategorieManagement $categorie;
    private AuthProviderInterface $authProvider;
    private CsrfTokenProviderInterface $csrfTokenProvider;

    public function __construct(){
        $this->template = 'pages/ViewCreationEvent.twig';
        $this->event = new EventManagement();
        $this->categorie = new CategorieManagement();
        $this->authProvider = new SessionAuthProvider();
        $this->csrfTokenProvider = new SessionCsrfTokenProvider();
    }

    public function __invoke($request, $response, $args)
    {
        if ($request->getMethod() == 'GET') {

            try {
                $this->authProvider->getSignedInUser();
            } catch (ProviderAuthentificationException $e) {
                throw new HttpUnauthorizedException($request, "Vous devez être connecté pour accéder à cette page.");
            }

            // On récupère le token CSRF pour le formulaire
            try {
                $csrfToken = $this->csrfTokenProvider->generateCsrf();
            } catch (CsrfException $e) {
                throw new HttpInternalServerErrorException($request, "Erreur lors de la génération du token CSRF : " . $e->getMessage());
            }

            $categories = $this->categorie->getCategories();
            $view = Twig::fromRequest($request);

            return $view->render($response, $this->template,
                ['categories'=> $categories, 'csrf_token' => $csrfToken]);
        } else {

            try {
                $user = $this->authProvider->getSignedInUser();
            } catch (ProviderAuthentificationException $e) {
                throw new HttpUnauthorizedException($request, "Vous devez être connecté pour accéder à cette page.");
            }

            $parseBody = $request->getParsedBody();

            // Vérification du token CSRF
            $csrfToken = $parseBody['csrf_token'] ?? null;
            try {
                $this->csrfTokenProvider->checkCsrf($csrfToken);
            } catch (CsrfException $e) {
                throw new HttpBadRequestException($request, "Le token CSRF est invalide ou manquant.");
            }

            $parseBody = $request->getParsedBody();
            $twig = Twig::fromRequest($request);
            $globals = $twig->getEnvironment()->getGlobals();
            $image = $request->getUploadedFiles()['image'] ?? null;
            if ($image instanceof UploadedFileInterface && $image->getError() === UPLOAD_ERR_OK) {
                $originalName = pathinfo($image->getClientFilename(), PATHINFO_BASENAME);
                $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $originalName);

                $destination = $globals['globals']['img_dir'] ."/". $filename;
                $destination = ltrim($destination, '/');
                $image->moveTo($destination);
            } else {
                $filename = null;
            }

            // Validation des données du formulaire
            if(FILTER_VAR($parseBody['titre'], FILTER_SANITIZE_SPECIAL_CHARS) === false ||
               FILTER_VAR($parseBody['description'], FILTER_SANITIZE_SPECIAL_CHARS) === false ||
               FILTER_VAR($parseBody['tarif'], FILTER_SANITIZE_SPECIAL_CHARS) === false ||
               empty($parseBody['titre']) || empty($parseBody['description']) ||
               empty($parseBody['tarif']) || empty($parseBody['date_debut']) ||
               !isset($parseBody['categorie'])) {
                throw new HttpBadRequestException($request, "Données du formulaire invalides.");
            }

            try {
                $this->event->createEvent(
                    $parseBody['titre'],
                    $parseBody['description'],
                    $parseBody['tarif'],
                    $parseBody['date_debut'],
                    $parseBody['date_fin'] ?? null,
                    $parseBody['horaire'] ?? null,
                    $filename,
                    $parseBody['categorie'],
                    $user['id']
                );

                $flash = new Messages();
                $routeParser = RouteContext::fromRequest($request)->getRouteParser();

                $flash->addMessage('success', "L'évènement a été créé avec succès.");
                return $response->withHeader('Location', $routeParser->urlFor('createEvent'))->withStatus(302);
            } catch (ExceptionInterne $e) {
                throw new HttpInternalServerErrorException($request, $e);
            } catch (EntityNotFoundException $e) {
                throw new HttpNotFoundException($request, "Catégorie ou créateur introuvable.");
            }
        }
    }
}