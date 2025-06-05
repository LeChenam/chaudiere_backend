<?php
namespace chaudiere\core\application\usecases;

interface CategorieManagementInterface{
    public function getCategories(): array;
}