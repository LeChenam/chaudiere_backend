<?php

namespace chaudiere\core\application\usecases;

use chaudiere\core\domain\entities\Categorie;

class CategorieManagement implements CategorieManagementInterface{

    public function getCategories(): array{
        $categories = Categorie::all();
        return $categories->toArray();
    }
}