<?php
namespace chaudiere\core\application\exceptions;
class ExceptionInterne extends \Exception
{
    public function __construct($message = "Erreur de base de données")
    {
        parent::__construct($message);
    }
}