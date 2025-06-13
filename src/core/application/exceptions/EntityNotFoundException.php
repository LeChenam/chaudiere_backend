<?php

namespace chaudiere\core\application\exceptions;

class EntityNotFoundException extends \Exception
{
    public function __construct($message = "Entity not found")
    {
        parent::__construct($message);
    }
}