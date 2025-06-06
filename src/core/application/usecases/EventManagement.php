<?php


namespace chaudiere\core\application\usecases;

use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\domain\entities\Categorie;
use chaudiere\core\domain\entities\Evenement;
use chaudiere\core\domain\entities\User;
use chaudiere\core\domain\exceptions\EntityNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventManagement implements EventManagementInterface
{
    public function createEvent($titre, $description, $tarif, $dateDebut, $dateFin, $horaire, $image, $categorie, $createur)
    {
        try {

            $categorie = Categorie::findOrFail($categorie);
            $createur = User::findOrFail($createur);
            $this->debug_to_console("passe8");


            $event = new Evenement();
            $event->id = Evenement::max("id") + 1;
            $event->titre = $titre;
            $event->description_md = $description;
            $event->tarif = $tarif;
            $event->date_debut = $dateDebut;
            $event->date_fin = $dateFin;
            $event->horaire = $horaire;
            $event->publie = false;
            $event->image_url = $image;

            $event->date_creation = date('Y-m-d H:i:s');
            $event->categorie()->associate($categorie);
            $event->createur()->associate($createur);
            $event->date_creation = date("Y-m-d H:i:s");
            $event->save();

        } catch (\Illuminate\Database\QueryException $e) {
            throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
        } catch (ModelNotFoundException $e) {
            throw new EntityNotFoundException("Catégorie ou créateur introuvable");
        } catch (\Exception $e) {
            throw new \RuntimeException("Erreur lors de la création de l'événement : " . $e->getMessage());
        }
    }

    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

}