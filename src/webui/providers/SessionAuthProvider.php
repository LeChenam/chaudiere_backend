<?php

namespace chaudiere\webui\providers;

class SessionAuthProvider implements AuthProviderInterface
{
    private AuthServiceInterface $authService;
    private String $sessionKey = 'auth_user';

    public function __construct(){
        $this->authService = new AuthService();
    }

    public function register(string $username, string $password): void
    {

    }

    public function loginByCredential(string $username, string $password): void
    {

    }


    public function getSignedInUser(): array
    {

    }

    public function logout(): void
    {

    }
}