<?php

namespace Fbender\Payonelink\Controller;

use Doctrine\ORM\EntityManager;
use Fbender\Payonelink\Model\Link;
use Fbender\Payonelink\Service\PayoneLinkService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Twig\Environment;

class LinkController
{
    private Environment $twig;
    private PayoneLinkService $linkService;
    private EntityManager $em;

    public function __construct(PayoneLinkService $linkService, Environment $twig, EntityManager $em)
    {
        $this->twig = $twig;
        $this->linkService = $linkService;
        $this->em = $em;
    }

    public function listLinks(Response $response): Response
    {
        $linkRepository = $this->em->getRepository(Link::class);
        $links = $linkRepository->findAll();
        $response->getBody()->write($this->twig->render('ListLinksView.twig', [
            'links' => $links,
        ]));

        return $response;
    }

    public function getLinksRemote(Response $response): Response
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

    public function getLink(Response $response, string $linkId)
    {
        $linkRepository = $this->em->getRepository(Link::class);
        /** @var Link $link */
        $link = $linkRepository->findOneBy(['linkId' => $linkId]) ?? null;

        if ($link === null) {
            return $response->withStatus(404);
        }

        $response->getBody()->write($this->twig->render('SingleLinkView.twig', [
            'response' => json_encode(json_decode($link->getRawResponse()), JSON_PRETTY_PRINT),
        ]));

        return $response;
    }

    public function createLink(Request $request, Response $response, EntityManager $em): Response
    {
        $linkServiceResponse = $this->linkService->createLink($request, $em);

        if ($linkServiceResponse->getStatusCode() === 201) {
            $linkResponseBody = json_decode($linkServiceResponse->getBody(), true);
            $link = Link::fromResponse($linkResponseBody);
            $em->persist($link);
            $em->flush();
        }

        $response->getBody()->write($this->twig->render('LinkCreatedView.twig', [
            'response' => json_encode(json_decode($linkServiceResponse->getBody()), JSON_PRETTY_PRINT),
            'responseCode' => $linkServiceResponse->getStatusCode(),
            'link' => $linkResponseBody['link'] ?? null,
        ]));

        return $response;
    }
}