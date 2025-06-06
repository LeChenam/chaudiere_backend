<?php

namespace chaudiere\core\application\exceptions;

class AuthentificationException extends \Exception
{
    public function __construct($message = "Erreur d'authentification")
    {
        parent::__construct($message);
    }
}