<?php

use chaudiere\core\application\usecases\EventManagement;

class CreationEventAction{
    private string $template;
    private EventManagement $event;

    public function __construct(){
        $this->template = 'pages/ViewCreationEvent.twig';
        $this->event = new EventManagement();
    }

    public function __invoke($request, $response, $args)
    {
        $queryParam = $request->getQueryParams();
        try{
            $event = $this->event->createEvent(
                $queryParam['titre'],
                $queryParam['description'],
                $queryParam['tarif'],
                $queryParam['date_debut'],
                $queryParam['date_fin'],
                $queryParam['horaire'],
                true,
                $queryParam['image'],
                $queryParam['categorie'],
                $_SESSION['user'],
                date('Y-m-d')
            );
        }catch(\Exception $e){

        }
    }
}