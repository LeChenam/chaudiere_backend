<?php

namespace chaudiere\webui\actions;
use chaudiere\core\application\usecases\CategorieManagement;
use chaudiere\core\application\usecases\EventManagement;
use chaudiere\core\domain\entities\Categorie;
use Slim\Views\Twig;

class CreationEventAction{
    private string $template;
    private EventManagement $event;
    private CategorieManagement $categorie;

    public function __construct(){
        $this->template = 'pages/ViewCreationEvent.twig';
        $this->event = new EventManagement();
        $this->categorie = new CategorieManagement();
    }

    public function __invoke($request, $response, $args)
    {
        if ($request->getMethod() == 'GET') {
            $categories = $this->categorie->getCategories();
            $view = Twig::fromRequest($request);
            return $view->render($response, $this->template, ['categories'=> $categories]);
        }
        else if($request->getMethod() == 'POST') {
            $queryParam = $request->getQueryParams();
            try {
                $this->event->createEvent(
                    $queryParam['titre'],
                    $queryParam['description'],
                    $queryParam['tarif'],
                    $queryParam['date_debut'],
                    $queryParam['date_fin'],
                    $queryParam['horaire'],
                    $queryParam['image'],
                    $queryParam['categorie'],
                    $_SESSION['user']
                );
                return $response->withHeader('Location', 'event/create')->withStatus(302);
            } catch (\Exception $e) {
                return $response->withStatus(500)->write('Error: ' . $e->getMessage());
            }
        }else return $response->withStatus(405);
    }
}