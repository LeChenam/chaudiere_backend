<?php
namespace chaudiere\api\actions;

use chaudiere\core\application\usecases\Collection;
use chaudiere\core\application\usecases\CollectionInterface;
use chaudiere\core\domain\exceptions\EntityNotFoundException;
use Slim\Exception\HttpNotFoundException;

class EventsByCategorieAction {

    private CollectionInterface $collection;
    public function __construct() {
        $this->collection = new Collection();
    }

    public function __invoke($request, $response, $args){
        $queryParams = $request->getQueryParams();
        $id = $args['id'] ?? null;
        $periode = $queryParams['periode'] ?? null;
        $rangement = $queryParams['sort'] ?? null;

        if ($id == null) {
            throw new \Slim\Exception\HttpBadRequestException($request, "Paramètre manquant");
        } else{
            if ($periode == null){
                if ($rangement == null){
                    //On tente de récupérer les catégories depuis le modèle
                    try {
                        $evenements = $this->collection->getEvenementsByCategorie($id);
                        $categorie = $this->collection->getCategorieById($id);
                    } catch (EntityNotFoundException $e) {
                        throw new HttpNotFoundException($request, $e->getMessage());
                    }

                    for ($i = 0; $i < count($evenements); $i++){
                        $link = '/api/evenements/'.$evenements[$i]['id'] ;
                        $evenements[$i]['links'] = ['self' => ['href' => $link]];
                    }

                    //Transformation des données
                    $data = [ 'type' => 'collection',
                        'categorie' => $categorie,
                        'count' => count($evenements),
                        'evenements' => $evenements ];
                    $response->getBody()->write(json_encode($data));
                } else{
                    //On tente de récupérer les catégories depuis le modèle
                    try {
                        $evenements = $this->collection->getSortedEventsByCategorie($id, $rangement);
                        $categorie = $this->collection->getCategorieById($id);
                    } catch (EntityNotFoundException $e) {
                        throw new HttpNotFoundException($request, $e->getMessage());
                    }

                    for ($i = 0; $i < count($evenements); $i++){
                        $link = '/api/evenements/'.$evenements[$i]['id'] ;
                        $evenements[$i]['links'] = ['self' => ['href' => $link]];
                    }

                    //Transformation des données
                    $data = [ 'type' => 'collection',
                        'categorie' => $categorie,
                        'count' => count($evenements),
                        'evenements' => $evenements ];
                    $response->getBody()->write(json_encode($data));
                }
            } else{
                //On tente de récupérer les catégories depuis le modèle
                try {
                    $periodetab = explode(",",$periode);
                    //On tente de récupérer les catégories depuis le modèle
                    $evenements = [];
                    for ($i = 0; $i < count($periodetab); $i++){
                        $evenements = array_merge($evenements, $this->collection->getEventsByCategByPeriode($id, $periodetab[$i]));
                    }
                    $categorie = $this->collection->getCategorieById($id);
                } catch (EntityNotFoundException $e) {
                    throw new HttpNotFoundException($request, $e->getMessage());
                }

                for ($i = 0; $i < count($evenements); $i++){
                    $link = '/api/evenements/'.$evenements[$i]['id'] ;
                    $evenements[$i]['links'] = ['self' => ['href' => $link]];
                }

                //Transformation des données
                $data = [ 'type' => 'collection',
                    'categorie' => $categorie,
                    'periode' => $periode,
                    'count' => count($evenements),
                    'evenements' => $evenements ];
                $response->getBody()->write(json_encode($data));
            }
        }

        //renvoie des données
        return
            $response->withHeader('Content-Type','application/json')
                ->withStatus(200);
    }
}