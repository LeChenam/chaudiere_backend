<?php
namespace chaudiere\api\actions;

use chaudiere\core\application\usecases\Collection;
use chaudiere\core\application\usecases\CollectionInterface;
use chaudiere\core\domain\exceptions\EntityNotFoundException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

class EventsByPeriodeAction {

    private CollectionInterface $collection;
    public function __construct() {
        $this->collection = new Collection();
    }

    public function __invoke($request, $response, $args){
        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();
        $periode = $routeParser['periode'];

        if ($periode == null) {
            throw new \Slim\Exception\HttpBadRequestException($request, "Paramètre manquant");
        } else{
            //On tente de récupérer les catégories depuis le modèle
            try {
                $evenements = $this->collection->getEvenementsByPeriode($periode);
            } catch (EntityNotFoundException $e) {
                throw new HttpNotFoundException($request, $e->getMessage());
            }

            //Transformation des données
            $data = [ 'type' => 'collection',
                'periode' => $periode,
                'count' => count($evenements),
                'evenement' => $evenements ];
            $response->getBody()->write(json_encode($data));
        }

        //renvoie des données
        return
            $response->withHeader('Content-Type','application/json')
                ->withStatus(200);
    }
}