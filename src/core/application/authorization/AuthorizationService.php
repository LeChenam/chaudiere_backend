<?php

namespace chaudiere\core\application\authorization;

use chaudiere\core\application\auth\AuthService;
use chaudiere\core\application\auth\AuthServiceInterface;
use chaudiere\core\application\exceptions\AuthentificationException;
use chaudiere\core\application\exceptions\AuthorizationException;
use chaudiere\core\application\exceptions\ExceptionInterne;
use chaudiere\core\domain\exceptions\EntityNotFoundException;

class AuthorizationService implements AuthorizationServiceInterface
{

    private AuthServiceInterface $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * @throws AuthentificationException
     * @throws AuthorizationException
     */
    public function isAuthorized(string $userId, string $action, ?string $resourceId = null): bool
    {
        try {
            $user = $this->authService->getUserById($userId);
        } catch (ExceptionInterne|EntityNotFoundException $e) {
            throw new AuthentificationException('Utilisateur non trouvé ou erreur interne: ' . $e->getMessage());
        }

        return match ($action) {
            AuthorizationServiceInterface::PERMISSION_CREATE_EVENT, AuthorizationServiceInterface::PERMISSION_UPDATE_EVENT, AuthorizationServiceInterface::PERMISSION_DELETE_EVENT => true,
            AuthorizationServiceInterface::PERMISSION_CREATE_USER => $this->isSuperAdmin($user['id']),
            default => throw new AuthorizationException('Action non autorisée: ' . $action),
        };
    }

    /**
     * @throws AuthentificationException
     */
    public function isSuperAdmin(string $userId): bool
    {
        try {
            $user = $this->authService->getUserById($userId);
        } catch (ExceptionInterne|EntityNotFoundException $e) {
            throw new AuthentificationException('Utilisateur non trouvé ou erreur interne: ' . $e->getMessage());
        }

        return $user['super_admin'] === 1;
    }
}