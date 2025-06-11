<?php

namespace chaudiere\api\providers;

use Psr\Http\Message\ServerRequestInterface;

interface EventsLinksProviderInterface
{
    public function generateEventLinks(array $events, ServerRequestInterface $request): array;
    public function generateEventImageLinks(array $events, ServerRequestInterface $request): array;
}