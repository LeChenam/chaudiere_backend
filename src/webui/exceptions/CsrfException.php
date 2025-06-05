<?php

namespace chaudiere\webui\exceptions;

class CsrfException extends \Exception
{
    public function __construct(string $message = "Token invalide")
    {
        parent::__construct($message);
    }
}