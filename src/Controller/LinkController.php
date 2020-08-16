<?php
namespace Fbender\Payonelink\Controller;

use Fbender\Payonelink\Service\PayoneLinkService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class LinkController {
    private Environment $twig;
    private PayoneLinkService $linkService;

    public function __construct(PayoneLinkService $linkService)
    {
        $loader = new FilesystemLoader(__DIR__ . '/../View/');
        $this->twig = new Environment($loader);

        $this->linkService = $linkService;
    }

    public function listLinks(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->twig->render('ListLinksView.twig'));

        return $response;
    }

    public function createLink(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $linkServiceResponse = $this->linkService->createLink($request);

        if ($linkServiceResponse->getStatusCode() === 201) {
            $link = json_decode($linkServiceResponse->getBody(), true);
        }

        try {
            $response->getBody()->write($this->twig->render('LinkCreatedView.twig', [
                'response' => json_encode(json_decode($linkServiceResponse->getBody()), JSON_PRETTY_PRINT),
                'responseCode' => $linkServiceResponse->getStatusCode(),
                'link' => $link['link'] ? $link['link'] : NULL,
            ]));
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }

        return $response;
    }
}