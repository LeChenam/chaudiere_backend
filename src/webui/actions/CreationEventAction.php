<?php

namespace chaudiere\webui\actions;
use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\application\usecases\CategorieManagement;
use chaudiere\core\application\usecases\EventManagement;
use chaudiere\core\domain\entities\Categorie;
use chaudiere\core\domain\exceptions\EntityNotFoundException;
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
            $_SESSION['user'] = "550e8400-e29b-41d4-a716-446655440000";
            if(!isset($_SESSION['user'])) return $response->withStatus(401);
            $parseBody = $request->getParsedBody();
            $image = $request->getUploadedFiles()['image'];
            if ($image instanceof \Psr\Http\Message\UploadedFileInterface && $image->getError() === UPLOAD_ERR_OK) {
                $filename = uniqid() . '_' . $image->getClientFilename();
            } else {
                $filename = null;
            }
            try {
                $this->event->createEvent(
                    $parseBody['titre'],
                    $parseBody['description'],
                    $parseBody['tarif'],
                    $parseBody['date_debut'],
                    $parseBody['date_fin'],
                    $parseBody['horaire'],
                    $filename,
                    $parseBody['categorie'],
                    $_SESSION['user']
                );
                $this->debug_to_console("passe1");
                return $response->withHeader('Location', 'createEvent')->withStatus(302);
            } catch (ExceptionInterne $e) {
                return $response->withStatus(500)->write('Error database: ' . $e->getMessage());
            }catch (EntityNotFoundException $e) {
                return $response->withStatus(500)->write('Error model: ' . $e->getMessage());
            }
        }else return $response->withStatus(405);
    }
    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }
}