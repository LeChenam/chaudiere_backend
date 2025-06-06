<?php

namespace chaudiere\core\application\usecases;

use chaudiere\core\domain\entities\Categorie;
use chaudiere\core\application\exceptions\ExceptionInterne;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

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
        } catch (QueryException $e) {
            throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
        }
    }
}