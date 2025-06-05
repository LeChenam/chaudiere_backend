<?php
namespace chaudiere\core\application\usecases;

use \Illuminate\Database\QueryException;
use chaudiere\core\application\exceptions\ExceptionDatabase;
use chaudiere\core\domain\entities\Categorie;
use chaudiere\core\domain\entities\Evenement;
use chaudiere\core\application\usecases\CollectionInterface;
use chaudiere\core\domain\exceptions\EntityNotFoundException;

class Collection implements CollectionInterface
{

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionDatabase
     */
    public function getCategories(): array{
        try {
            $categories = Categorie::all();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Table Categorie introuvable");
        } catch (QueryException $e) {
            throw new ExceptionDatabase("Erreur de requête : " . $e->getMessage());
        }
        return $categories->toArray();
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionDatabase
     */
    public function getCategorieById(int $id): array
    {
        try {
            $categorie = Categorie::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Categorie $id introuvable");
        } catch (QueryException $e) {
            throw new ExceptionDatabase("Erreur de requête : " . $e->getMessage());
        }
        return $categorie->toArray();
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionDatabase
     */
    public function getEvenementById(string $id): array
    {
        try {
            $prestation = Evenement::find($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Prestation $id introuvable");
        } catch (QueryException $e) {
            throw new ExceptionDatabase("Erreur de requête : " . $e->getMessage());
        }
        return $prestation->toArray();
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionDatabase
     */
    public function getEvenementsByCategorie(int $categ_id): array
    {
        try {
            $categorie = Categorie::findOrFail($categ_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Categorie $categ_id introuvable");
        } catch (QueryException $e) {
            throw new ExceptionDatabase("Erreur de requête : " . $e->getMessage());
        }
        return $categorie->evenements->toArray();
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionDatabase
     */
    public function getEvenements(): array
    {
        try {
            $prestations = Evenement::all();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Table Prestation introuvable");
        } catch (QueryException $e) {
            throw new ExceptionDatabase("Erreur de requête : " . $e->getMessage());
        }
        return $prestations->toArray();
    }
}