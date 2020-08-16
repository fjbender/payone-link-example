<?php
namespace Fbender\Payonelink\Controller;

use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class MainController
{
    private Environment $twig;

    public function __construct()
    {
        // todo: should be possible to get this through DI
        $loader = new FilesystemLoader(__DIR__ . '/../View/');
        $twig = new Environment($loader);
        $this->twig = $twig;
    }

    public function get(ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->twig->render('MainView.twig'));

        return $response;
    }
}