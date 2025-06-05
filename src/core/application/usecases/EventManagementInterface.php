<?php
namespace chaudiere\core\application\usecases;

interface EventManagementInterface{
    public function createEvent($titre, $description, $tarif, $dateDebut, $dateFin, $horaire, $publie, $image, $categorie, $createur, $dateCreation);

}