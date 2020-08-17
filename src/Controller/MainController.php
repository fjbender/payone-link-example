<?php

namespace Fbender\Payonelink\Controller;

use Slim\Psr7\Response;
use Twig\Environment;

class MainController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function home(Response $response): Response
    {
        $response->getBody()->write($this->twig->render('HomeView.twig'));

        return $response;
    }

    public function createLinkForm(Response $response): Response
    {
        $response->getBody()->write($this->twig->render('CreateLinkView.twig'));

        return $response;
    }
}