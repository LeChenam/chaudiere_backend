<?php
namespace chaudiere\core\application\usecases;

interface EvenementManagementInterface{
    public function createEvenement($titre, $description, $tarif, $dateDebut, $dateFin, $horaire, $publie, $image, $categorie, $auteur, $dateCreation);

}