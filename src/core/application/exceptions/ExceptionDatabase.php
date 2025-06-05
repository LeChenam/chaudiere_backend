<?php

namespace chaudiere\core\application\exceptions;
class ExceptionDatabase extends \Exception
{
    public function __construct($message = "Erreur de base de données")
    {
        parent::__construct($message);
    }
}