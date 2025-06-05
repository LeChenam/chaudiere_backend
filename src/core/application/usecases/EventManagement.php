<?php


namespace chaudiere\core\application\usecases;

use chaudiere\core\domain\entities\Evenement;

class EventManagement implements EventManagementInterface
{
    public function createEvent($titre, $description, $tarif, $dateDebut, $dateFin, $horaire, $publie, $image, $categorie, $createur, $dateCreation)
    {
        try {
            $event = new Evenement();
            $event->titre = $titre;
            $event->description_md = $description;
            $event->tarif = $tarif;
            $event->date_debut = $dateDebut;
            $event->date_fin = $dateFin;
            $event->horaire = $horaire;
            $event->publie = $publie;
            $event->image_url = $image;
            $event->categorie()->associate($categorie);
            $event->createur()->associate($createur);
            $event->date_creation = $dateCreation;
            $event->save();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Table introuvable");
        } catch (\Illuminate\Database\QueryException $e){
            throw new ExceptionDatabase("Erreur de requête : " . $e->getMessage());
        }
    }
}