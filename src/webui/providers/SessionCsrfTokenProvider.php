<?php
namespace chaudiere\webui\providers;

use chaudiere\webui\exceptions\CsrfException;
use Random\RandomException;

class SessionCsrfTokenProvider implements CsrfTokenProviderInterface{

    private string $sessionName;

    public function __construct(string $sessionName = 'csrf_token'){
        $this->sessionName = $sessionName;
    }


    /**
     * @throws CsrfException
     */
    public function generateCsrf(): string
    {
        try {
            $token = bin2hex(random_bytes(32));
        } catch (RandomException $e) {
            throw new CsrfException("Erreur lors de la génération du token CSRF: " . $e->getMessage());
        }
        $_SESSION[$this->sessionName] = $token;
        return $token;
    }

    /**
     * @throws CsrfException
     */
    public function checkCsrf($token): void
    {
        if($token != $_SESSION[$this->sessionName]){
            throw new CsrfException();
        }
        unset($_SESSION[$this->sessionName]);
    }
}