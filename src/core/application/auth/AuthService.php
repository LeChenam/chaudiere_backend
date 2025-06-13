<?php

namespace chaudiere\core\application\auth;

use chaudiere\core\application\exceptions\AuthentificationException;
use chaudiere\core\application\exceptions\EntityNotFoundException;
use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\domain\entities\User;
use Ramsey\Uuid\Uuid;

class AuthService implements AuthServiceInterface
{

    /**
     * @throws ExceptionInterne
     * @throws AuthentificationException
     */
    public function register(string $email, string $password): string
    {

        $user = User::where('email', $email)
            ->first();
        if ($user !== null) {
            throw new AuthentificationException("L'email est déjà utilisé");
        }
        $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
        try {
            $user = new User();
            $user->id = Uuid::uuid4()->toString();
            $user->email = $email;
            $user->password = $password;
            $user->date_creation = date('Y-m-d H:i:s');
            $user->save();
            return $user->id;
        } catch (\Illuminate\Database\QueryException $e) {
            throw new ExceptionInterne('Erreur de base de donnée: ' . $e->getMessage());
        }
    }

    /**
     * @throws ExceptionInterne
     * @throws AuthentificationException
     */
    public function loginByCredential(string $email, string $password): string
    {
        try {
            $user = User::where('email', $email)
                ->first();
            if($user === null) {
                throw new ExceptionInterne('Utilisateur non trouvé');
            }
            if(!password_verify($password, $user->password)) {
                throw new AuthentificationException('Mot de passe incorrect');
            }
            return $user->id;
        } catch (\Illuminate\Database\QueryException $e) {
            throw new ExceptionInterne('Erreur de base de donnée: ' . $e->getMessage());
        }
    }

    /**
     * @throws ExceptionInterne
     * @throws EntityNotFoundException
     */
    public function getUserById(string $userId): array
    {
        try {
            $user = User::find($userId);
            if ($user === null) {
                throw new EntityNotFoundException('Utilisateur non trouvé');
            }
            return [
                'id' => $user->id,
                'email' => $user->email,
                'super_admin' => $user->est_superadmin,
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            throw new ExceptionInterne('Erreur de base de donnée: ' . $e->getMessage());
        }
    }
}