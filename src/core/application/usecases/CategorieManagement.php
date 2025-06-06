<?php

namespace chaudiere\core\application\usecases;

use chaudiere\core\domain\entities\Categorie;
use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\domain\exceptions\EntityNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

class CategorieManagement implements CategorieManagementInterface{

    public function getCategories(): array{
        $categories = Categorie::all();
        return $categories->toArray();
    }

    public function createCategorie(string $nom, string $description){
        try{
            $categorie = new Categorie();
            $categorie->id = null; // Auto-incremented by the database
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