<?php
namespace chaudiere\core\application\usecases;

use \Illuminate\Database\QueryException;
use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\domain\entities\Categorie;
use chaudiere\core\domain\entities\Evenement;
use chaudiere\core\application\usecases\CollectionInterface;
use chaudiere\core\domain\exceptions\EntityNotFoundException;

class Collection implements CollectionInterface
{

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionInterne
     */
    public function getCategories(): array{
        try {
            $categories = Categorie::all();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Table Categorie introuvable");
        } catch (QueryException $e) {
            throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
        }
        return $categories->toArray();
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionInterne
     */
    public function getCategorieById(int $id): array
    {
        try {
            $categorie = Categorie::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Categorie $id introuvable");
        } catch (QueryException $e) {
            throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
        }
        return $categorie->toArray();
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionInterne
     */
    public function getEvenementById(string $id): array
    {
        try {
            $prestation = Evenement::find($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Prestation $id introuvable");
        } catch (QueryException $e) {
            throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
        }
        return $prestation->toArray();
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionInterne
     */
    public function getEvenementsByCategorie(int $categ_id): array
    {
        try {
            $categorie = Categorie::findOrFail($categ_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Categorie $categ_id introuvable");
        } catch (QueryException $e) {
            throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
        }
        return $categorie->evenements->toArray();
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionInterne
     */
    public function getEvenements(): array
    {
        try {
            $evenements = Evenement::all();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Table Prestation introuvable");
        } catch (QueryException $e) {
            throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
        }
        return $evenements->toArray();
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionInterne
     */
    public function getEvenementsByPeriode(string $periode): array
    {
        switch ($periode){
            case "courante":
                try {
                    $ds = strtotime("now");
                    $eventsbyperiode = Evenement::whereBetween('date_debut', [date("Y-m-01", $ds), date("Y-m-31", $ds)])->get();
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "passee":
                try {
                    $ds = strtotime("now");
                    $eventsbyperiode = Evenement::where('date_debut', '<', date("Y-m-01", $ds))->get();
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "futur":
                try {
                    $ds = strtotime("now");
                    $eventsbyperiode = Evenement::where('date_debut', '>', date("Y-m-31", $ds))->get();
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            default:
            throw new ExceptionInterne("Erreur de requête : Mauvaise requête");
        }

        return $eventsbyperiode->toArray();
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionInterne
     */
    public function getEvenementsRanges(string $rangement): array
    {
        switch ($rangement){
            case "date-asc":
                try {
                    $evenementsranges = Evenement::orderBy('date_debut', 'asc')->get();
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "date-desc":
                try {
                    $evenementsranges = Evenement::orderBy('date_debut', 'desc')->get();
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "titre":
                try {
                    $evenementsranges = Evenement::orderBy('titre', 'asc')->get();
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "categorie":
                try {
                    $evenementsranges = Evenement::orderBy('categorie_id', 'asc')->get();
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            default:
            throw new ExceptionInterne("Erreur de requête : Mauvaise requête");
        }
        
        return $evenementsranges->toArray();
    }
}