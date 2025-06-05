<?php

namespace chaudiere\webui\providers;

interface CsrfTokenProviderInterface
{
    public function generateCsrf(): string;
    public function checkCsrf(string $token): void;
}