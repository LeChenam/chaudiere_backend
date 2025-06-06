<?php

namespace chaudiere\core\application\auth;

interface AuthServiceInterface
{
    public function register(string $email, string $password): string;
    public function loginByCredential(string $email, string $password): string;
    public function getUserById(string $userId): array;
}