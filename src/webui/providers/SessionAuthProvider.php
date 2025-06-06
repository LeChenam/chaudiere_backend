<?php

namespace chaudiere\webui\providers;

use chaudiere\core\application\auth\AuthService;
use chaudiere\core\application\auth\AuthServiceInterface;
use chaudiere\core\application\exceptions\AuthentificationException;
use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\domain\exceptions\EntityNotFoundException;
use chaudiere\webui\exceptions\ProviderAuthentificationException;

class SessionAuthProvider implements AuthProviderInterface
{
    private AuthServiceInterface $authService;
    private String $sessionKey = 'auth_user';

    public function __construct(){
        $this->authService = new AuthService();
    }

    /**
     * @throws ProviderAuthentificationException
     */
    public function register(string $email, string $password): void
    {
        try {
            $this->authService->register($email, $password);
        } catch (AuthentificationException|ExceptionInterne $e) {
            throw new ProviderAuthentificationException($e->getMessage());
        }
    }

    /**
     * @throws ProviderAuthentificationException
     */
    public function loginByCredential(string $email, string $password): void
    {
        try {
            $userID = $this->authService->loginByCredential($email, $password);
            $_SESSION[$this->sessionKey] = $this->authService->getUserById($userID);
        } catch (AuthentificationException|ExceptionInterne $e) {
            throw new ProviderAuthentificationException($e->getMessage());
        } catch (EntityNotFoundException $e) {
            throw new ProviderAuthentificationException("Utilisateur non trouvé : " . $e->getMessage());
        }
    }


    /**
     * @throws ProviderAuthentificationException
     */
    public function getSignedInUser(): array
    {
        if (isset($_SESSION[$this->sessionKey]) && is_array($_SESSION[$this->sessionKey])) {
            return $_SESSION[$this->sessionKey];
        }
        throw new ProviderAuthentificationException("Aucun utilisateur connecté");
    }

    public function logout(): void
    {
        if (isset($_SESSION[$this->sessionKey])) {
            unset($_SESSION[$this->sessionKey]);
        }
        session_destroy();
    }
}