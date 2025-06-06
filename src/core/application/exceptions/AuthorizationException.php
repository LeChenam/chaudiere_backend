<?php

namespace chaudiere\core\application\exceptions;

class AuthorizationException extends \Exception
{
    public function __construct($message = "Erreur d'autorisation")
    {
        parent::__construct($message);
    }
}