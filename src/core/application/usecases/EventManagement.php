<?php


namespace chaudiere\core\application\usecases;

use chaudiere\core\application\exceptions\EntityNotFoundException;
use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\domain\entities\Categorie;
use chaudiere\core\domain\entities\Evenement;
use chaudiere\core\domain\entities\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventManagement implements EventManagementInterface
{
    /**
     * @throws ExceptionInterne
     * @throws EntityNotFoundException
     */
    public function createEvent(string $titre, string $description, string $tarif, string $dateDebut, ?string $dateFin, ?string $horaire, ?string $image, int $categorie, string $createur): void
    {
        try {

            $categorie = Categorie::findOrFail($categorie);
            $createur = User::findOrFail($createur);

            $event = new Evenement();
            $event->titre = $titre;
            $event->description_md = $description;
            $event->tarif = $tarif;
            $event->date_debut = $dateDebut;
            if($dateFin) {
                $event->date_fin = $dateFin;
            }
            if($horaire) {
                $event->horaire = $horaire;
            }
            $event->publie = false;
            if($image){
                $event->image = $image;
            }
            $event->date_creation = date('Y-m-d H:i:s');

            $event->categorie()->associate($categorie);
            $event->createur()->associate($createur);
            $event->save();

        } catch (\Illuminate\Database\QueryException $e) {
            throw new ExceptionInterne("Erreur de requête : " . $e->getMessage());
        } catch (ModelNotFoundException $e) {
            throw new EntityNotFoundException("Catégorie ou créateur introuvable");
        } catch (\Exception $e) {
            throw new ExceptionInterne("Erreur lors de la création de l'événement : " . $e->getMessage());
        }
    }

    /**
     * @throws ExceptionInterne
     * @throws \chaudiere\core\application\exceptions\EntityNotFoundException
     */
    public function publishEvent(int $eventId): void
    {
        try {
            $event = Evenement::findOrFail($eventId);
            $event->publie = true;
            $event->save();
        } catch (ModelNotFoundException $e) {
            throw new EntityNotFoundException("Événement introuvable");
        } catch (\Exception $e) {
            throw new ExceptionInterne("Erreur lors de la publication de l'événement : " . $e->getMessage());
        }
    }
}