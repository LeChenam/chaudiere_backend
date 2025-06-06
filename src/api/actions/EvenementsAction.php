<?php
namespace chaudiere\api\actions;

use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\application\usecases\Collection;
use chaudiere\core\application\usecases\CollectionInterface;
use chaudiere\core\domain\exceptions\EntityNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

class EvenementsAction {

    private CollectionInterface $collection;
    public function __construct() {
        $this->collection = new Collection();
    }

    public function __invoke($request, $response, $args){
        $queryParams = $request->getQueryParams();
        $id = $args['id'] ?? null;
        $periode = $queryParams['periode'] ?? null;

        if ($periode == null) {
            if ($id == null) {
                try {
                    $evenements = $this->collection->getEvenements();
                } catch (EntityNotFoundException $e) {
                    throw new HttpNotFoundException($request, $e->getMessage());
                }

                //Transformation des données
                $data = [ 'type' => 'collection',
                    'count' => count($evenements),
                    'evenements' => $evenements ];
                $response->getBody()->write(json_encode($data));

            } else{
                //On tente de récupérer les catégories depuis le modèle
                try {
                    $evenement = $this->collection->getEvenementById($id);
                } catch (EntityNotFoundException $e) {
                    throw new HttpNotFoundException($request, $e->getMessage());
                }

                //Transformation des données
                $data = [ 'type' => 'ressource',
                    'evenement' => $evenement ];
                $response->getBody()->write(json_encode($data));
            }
        } else{
            $periodetab = explode(",",$periode);
            //On tente de récupérer les catégories depuis le modèle
            $evenements = [];
            for ($i = 0; $i < count($periodetab); $i++){
                try {
                    $evenements = array_merge($evenements, $this->collection->getEvenementsByPeriode($periodetab[$i]));
                } catch (EntityNotFoundException $e) {
                    throw new HttpNotFoundException($request, $e->getMessage());
                } catch (ExceptionInterne $e) {
                    throw new HttpInternalServerErrorException($request, $e->getMessage());
                }
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