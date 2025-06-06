<?php
namespace chaudiere\webui\actions;

use chaudiere\core\domain\entities\Categorie;
use chaudiere\core\application\usecases\CategorieManagement;
use Slim\Views\Twig;

class CreationCategorieAction{
    private string $template;
    private CategorieManagement $categorieManagement;

    public function __construct(){
        $this->template = 'pages/ViewCreationCategorie.twig';
        $this->categorieManagement = new CategorieManagement();
    }

    public function __invoke($request, $response, $args)
    {
        if( $request->getMethod() == 'GET') {
            $view = Twig::fromRequest($request);
            return $view->render($response, $this->template);
        } elseif ($request->getMethod() == 'POST') {
            $queryParam = $request->getQueryParams();
            try{
                $this->categorieManagement->createCategorie(
                    $queryParam['nom'],
                    $queryParam['description']

                );
                return $response->withHeader('Location', 'categorie/create')->withStatus(302);
            } catch (\Exception $e) {
                return $response->withStatus(500)->write('Error: ' . $e->getMessage());
            }
        } else {
            return $response->withStatus(405);
        }
    }
}

