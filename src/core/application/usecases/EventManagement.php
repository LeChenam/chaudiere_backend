<?php


namespace chaudiere\core\application\usecases;

use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\domain\entities\Evenement;
use Illuminate\Contracts\Queue\EntityNotFoundException;

class EventManagement implements EventManagementInterface
{
    public function createEvent($titre, $description, $tarif, $dateDebut, $dateFin, $horaire, $image, $categorie, $createur)
    {
        try {
            $event = new Evenement();
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
            $event->save();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Table introuvable");
        } catch (\Illuminate\Database\QueryException $e){
            throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
        }
    }

}