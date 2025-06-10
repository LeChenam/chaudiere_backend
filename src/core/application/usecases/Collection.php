<?php
namespace chaudiere\core\application\usecases;

use \Illuminate\Database\QueryException;
use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\domain\entities\Categorie;
use chaudiere\core\domain\entities\Evenement;
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
            $prestation = Evenement::where('id', $id)
                ->where('publie', true)
                ->firstOrFail(['id', 'titre', 'description_md', 'tarif', 'date_debut', 'date_fin', 'horaire', 'image', 'categorie_id']);
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
            $evenements = Evenement::where("categorie_id","=",$categ_id)->get(["id","titre", "date_debut","categorie_id"]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Categorie $categ_id introuvable");
        } catch (QueryException $e) {
            throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
        }
        return $evenements->evenements->where('publie', true)->toArray();
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionInterne
     */
    public function getEvenements(): array
    {
        try {
            $evenements = Evenement::where('publie', true)->get(["id","titre", "date_debut","categorie_id"]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new EntityNotFoundException("Table Prestation introuvable");
        } catch (QueryException $e) {
            throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
        }
        return $evenements->toArray();
    }

    /**
     * @throws ExceptionInterne
     * @throws EntityNotFoundException
     */
    public function getCreatedEvenements(): array
    {
        try {
            $evenements = Evenement::all();
            foreach ($evenements as $evenement) {
                $evenement->category = $evenement->categorie->libelle; // Assure-toi que le champ s'appelle 'nom'
            }
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
                    $eventsbyperiode = Evenement::whereBetween('date_debut', [date("Y-m-01", $ds), date("Y-m-31", $ds)])->get(["id","titre", "date_debut","categorie_id"])->where('publie', true);
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "passee":
                try {
                    $ds = strtotime("now");
                    $eventsbyperiode = Evenement::where('date_debut', '<', date("Y-m-01", $ds))->get(["id","titre", "date_debut","categorie_id"])->where('publie', true);
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "futur":
                try {
                    $ds = strtotime("now");
                    $eventsbyperiode = Evenement::where('date_debut', '>', date("Y-m-31", $ds))->get(["id","titre", "date_debut","categorie_id"])->where('publie', true);
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
    public function getEventsByCategByPeriode(int $categ_id, string $periode): array
    {
        switch ($periode){
            case "courante":
                try {
                    $ds = strtotime("now");
                    $eventsbycategbyperiode = Evenement::where("categorie_id","=",$categ_id)->whereBetween('date_debut', [date("Y-m-01", $ds), date("Y-m-31", $ds)])->get(["id","titre", "date_debut","categorie_id"]);
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "passee":
                try {
                    $ds = strtotime("now");
                    $eventsbycategbyperiode = Evenement::where("categorie_id","=",$categ_id)->where('date_debut', '<', date("Y-m-01", $ds))->get(["id","titre", "date_debut","categorie_id"]);
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "futur":
                try {
                    $ds = strtotime("now");
                    $eventsbycategbyperiode = Evenement::where("categorie_id","=",$categ_id)->where('date_debut', '>', date("Y-m-31", $ds))->get(["id","titre", "date_debut","categorie_id"]);
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            default:
            throw new ExceptionInterne("Erreur de requête : Mauvaise requête");
        }

        return $eventsbycategbyperiode->toArray();
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
                    $evenementsranges = Evenement::where('publie', true)->orderBy('date_debut', 'asc')->get(["id","titre", "date_debut","categorie_id"]);
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "date-desc":
                try {
                    $evenementsranges = Evenement::where('publie', true)->orderBy('date_debut', 'desc')->get(["id","titre", "date_debut","categorie_id"]);
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "titre":
                try {
                    $evenementsranges = Evenement::where('publie', true)->orderBy('titre', 'asc')->get(["id","titre", "date_debut","categorie_id"]);
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "categorie":
                try {
                    $evenementsranges = Evenement::where('publie', true)->orderBy('categorie_id', 'asc')->get(["id","titre", "date_debut","categorie_id"]);
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

    /**
     * @throws ExceptionInterne
     * @throws EntityNotFoundException
     */
    public function getSortedEventsByCategorie(int $categ_id, string $rangement): array
    {
        switch ($rangement){
            case "date-asc":
                try {
                    $evenementsranges = Evenement::where("categorie_id","=",$categ_id)->orderBy('date_debut', 'asc')->get(["id","titre", "date_debut","categorie_id"]);
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "date-desc":
                try {
                    $evenementsranges = Evenement::where("categorie_id","=",$categ_id)->orderBy('date_debut', 'desc')->get(["id","titre", "date_debut","categorie_id"]);
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    throw new EntityNotFoundException("Table Evenement introuvable");
                } catch (QueryException $e) {
                    throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
                }
                break;
            case "titre":
                try {
                    $evenementsranges = Evenement::where("categorie_id","=",$categ_id)->orderBy('titre', 'asc')->get(["id","titre", "date_debut","categorie_id"]);
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