<?php
namespace chaudiere\core\application\usecases;

interface EventManagementInterface{
    public function createEvent(string $titre, string $description, string $tarif, string $dateDebut, ?string $dateFin, ?string $horaire, ?string $image, int $categorie, string $createur): void;

}