<?php

namespace chaudiere\core\application\usecases;

use chaudiere\core\application\exceptions\EntityNotFoundException;
use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\domain\entities\Categorie;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategorieManagement implements CategorieManagementInterface{

    public function getCategories(): array{
        $categories = Categorie::all();
        return $categories->toArray();
    }

    public function createCategorie(string $nom, string $description){
        try{
            $categorie = new Categorie();
            $categorie->libelle = $nom;
            $categorie->description_md = $description;
            $categorie->save();
        } catch(ModelNotFoundException $e) {
            throw new EntityNotFoundException("Table introuvable");
        } catch (ExceptionInterne $e) {
            throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
        }
    }
}