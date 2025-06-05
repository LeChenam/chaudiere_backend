<?php

class CreationEvenementAction{
    private string $template;

    public function __construct(){
        $this->template = 'pages/ViewCreationEvenement.twig';
    }

    public function __invoke($request, $response, $args)
    {
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, $this->template);
    }
}