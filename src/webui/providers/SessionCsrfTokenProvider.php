<?php
namespace chaudiere\webui\providers;

use chaudiere\webui\exceptions\CsrfException;

class SessionCsrfTokenProvider implements CsrfTokenProviderInterface{

    private string $sessionName;

    public function __construct(string $sessionName = 'csrf_token'){
        $this->sessionName = $sessionName;
    }


    public function generateCsrf(): string
    {
        $token = md5(uniqid(mt_rand(), true));
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