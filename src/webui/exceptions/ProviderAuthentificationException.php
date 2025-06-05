<?php

namespace chaudiere\webui\exceptions;

class ProviderAuthentificationException extends \Exception
{
    public function __construct($message = "Erreur d'authentification")
    {
        parent::__construct($message);
    }
}