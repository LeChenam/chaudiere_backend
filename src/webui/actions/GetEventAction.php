<?php
namespace chaudiere\webui\actions;

use chaudiere\core\application\usecases\EventManagement;
use chaudiere\core\domain\entities\Categorie;
use chaudiere\core\domain\entities\Evenement;
use Illuminate\Support\Facades\Event;

class  GetEventAction
{
    private string $template;
    private EventManagement $event;

    public function __construct()
    {
        $this->template = 'pages/ViewEvents.twig';
        $this->event = new EventManagement();
    }
    public function __invoke($request, $response, $args)
    {
        $events = Evenement::orderBy('date_debut', 'asc')->get();
        $categories = Categorie::all();
        $selectedCategoryId = $_GET['categorie'] ?? null;
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, $this->template, ['events'=> $events, 'categories' => $categories, 'selectedCategoryId' => $selectedCategoryId]);
    }
}