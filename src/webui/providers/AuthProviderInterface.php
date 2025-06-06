<?php

namespace chaudiere\webui\providers;

interface AuthProviderInterface
{
    public function register(string $email, string $password): void;
    public function loginByCredential(string $email, string $password): void;
    public function getSignedInUser(): array;
    public function logout(): void;
}