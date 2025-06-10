<?php
namespace chaudiere\webui\actions;

use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\application\usecases\Collection;
use chaudiere\core\application\usecases\CollectionInterface;
use chaudiere\core\application\usecases\EventManagement;
use chaudiere\core\domain\entities\Categorie;
use chaudiere\core\domain\entities\Evenement;
use chaudiere\core\domain\exceptions\EntityNotFoundException;
use chaudiere\webui\exceptions\ProviderAuthentificationException;
use chaudiere\webui\providers\AuthProviderInterface;
use chaudiere\webui\providers\CsrfTokenProviderInterface;
use chaudiere\webui\providers\SessionAuthProvider;
use chaudiere\webui\providers\SessionCsrfTokenProvider;
use Illuminate\Support\Facades\Event;
use Slim\Flash\Messages;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class  GetEventAction
{
    private string $template;
    private CollectionInterface $collection;
    private AuthProviderInterface $authProvider;
    private CsrfTokenProviderInterface $csrfTokenProvider;

    public function __construct()
    {
        $this->template = 'pages/ViewEvents.twig';
        $this->collection = new Collection();
        $this->authProvider = new SessionAuthProvider();
        $this->csrfTokenProvider = new SessionCsrfTokenProvider();
    }
    public function __invoke($request, $response, $args)
    {

        try {
            $this->authProvider->getSignedInUser();
        } catch (ProviderAuthentificationException $e) {

            $flash = new Messages();
            $flash->addMessage('error', "Vous devez être connecté pour accéder à cette page.");

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
        }

        try {
            $events = $this->collection->getCreatedEvenements();
        } catch (ExceptionInterne $e) {
            throw new Slim\Exception\HttpInternalServerErrorException($request, $e->getMessage());
        } catch (EntityNotFoundException $e) {
            throw new Slim\Exception\HttpNotFoundException($request, $e->getMessage());
        }

        try {
            $categories = $this->collection->getCategories();
        } catch (ExceptionInterne $e) {
            throw new Slim\Exception\HttpInternalServerErrorException($request, $e->getMessage());
        } catch (EntityNotFoundException $e) {
            throw new Slim\Exception\HttpNotFoundException($request, $e->getMessage());
        }

        $csrfToken = $this->csrfTokenProvider->generateCsrf();

        $view = Twig::fromRequest($request);
        return $view->render($response, $this->template, ['events'=> $events, 'categories' => $categories, 'csrf_token' => $csrfToken]);
    }
}