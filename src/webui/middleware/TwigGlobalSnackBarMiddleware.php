<?php

namespace chaudiere\webui\middleware;

use Slim\Views\Twig;

class TwigGlobalSnackBarMiddleware
{
    public function __invoke($request, $handler)
    {
        $view = Twig::fromRequest($request);
        $globals = $view->getEnvironment()->getGlobals();
        $flash = $globals['flash'];
        $messages = $flash->getMessages();

        // Ajouter les messages flash aux variables globales de Twig
        $view->getEnvironment()->addGlobal('flash_messages', $messages);

        return $handler->handle($request);
    }
}