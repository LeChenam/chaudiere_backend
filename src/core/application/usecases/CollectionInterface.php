<?php
namespace chaudiere\core\application\usecases;

Interface CollectionInterface{
    public function getCategories(): array;
    public function getCategorieById(int $id): array;
    public function getEvenementById(string $id): array;
    public function getEvenementsByCategorie(int $categ_id): array;
    public function getEvenements(): array;
    public function getCreatedEvenements(): array;
    public function getEvenementsByPeriode(string $periode): array;
    public function getEventsByCategByPeriode(int $categ_id, string $periode): array;
    public function getSortedEventsByCategorie(int $categ_id, string $periode): array;
    public function getEvenementsRanges(string $rangement): array;
}