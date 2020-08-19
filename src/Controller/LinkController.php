<?php

namespace Fbender\Payonelink\Controller;

use Fbender\Payonelink\Model\Link;
use Fbender\Payonelink\Service\PayoneLinkService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Twig\Environment;

class LinkController
{
    private Environment $twig;
    private PayoneLinkService $linkService;

    public function __construct(PayoneLinkService $linkService, Environment $twig)
    {
        $this->twig = $twig;
        $this->linkService = $linkService;
    }

    public function getLinks(Response $response): Response
    {
        $linkList = json_decode($this->linkService->getLinks()->getBody(), true);
        $links = array();
        foreach ($linkList['content'] as $linkEntry) {
            $links[] = Link::fromResponse($linkEntry);
        }
        $response->getBody()->write($this->twig->render('ListLinksView.twig', [
            'links' => $links
        ]));
        return $response;
    }

    public function getLink(Response $response, string $linkId): Response
    {
        $link = $this->linkService->getLink($linkId);

        if ($link === null) {
            return $response->withStatus(404);
        }

        $response->getBody()->write($this->twig->render('SingleLinkView.twig', [
            'response' => json_encode(json_decode($link->getRawResponse()), JSON_PRETTY_PRINT),
            'link' => $link,
        ]));

        return $response;
    }

    public function createLink(Request $request, Response $response): Response
    {
        $linkServiceResponse = $this->linkService->createLink($request);

        if ($linkServiceResponse->getStatusCode() === 201) {
            $linkResponseBody = json_decode($linkServiceResponse->getBody(), true);
            $link = Link::fromResponse($linkResponseBody);
        }

        $response->getBody()->write($this->twig->render('LinkCreatedView.twig', [
            'response' => json_encode(json_decode($linkServiceResponse->getBody()), JSON_PRETTY_PRINT),
            'responseCode' => $linkServiceResponse->getStatusCode(),
            'link' => $link->getLink() ?? null,
        ]));

        return $response;
    }
}