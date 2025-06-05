<?php

namespace chaudiere\webui\providers;

interface AuthProviderInterface
{
    public function register(string $username, string $password): void;
    public function loginByCredential(string $username, string $password): void;
    public function getSignedInUser(): array;
    public function logout(): void;
}