<?php
namespace chaudiere\webui\actions;

class  GetEventAction
{
    private string $template;
    private E

    public function __construct()
    {
        $this->template = 'pages/ViewEvent.twig';
        $this->even
    }

    public function __invoke($request, $response, $args)
    {
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, $this->template);
    }
}