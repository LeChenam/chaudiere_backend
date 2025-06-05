<?php
namespace chaudiere\appli\core\application\usecases;

Interface CollectionInterface{
    public function getCategories(): array;
    public function getCategorieById(int $id): array;
    public function getEvenementById(string $id): array;
    public function getEvenementsByCategorie(int $categ_id): array;
    public function getEvenements(): array;
}