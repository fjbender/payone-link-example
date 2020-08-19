<?php

namespace Fbender\Payonelink\Service;

use Fbender\Payonelink\Model\Link;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


class PayoneLinkService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getLinks(): ResponseInterface
    {
        $request = new Request('GET', $this->buildGetAllLinksUrl(),
            ['Authorization' => $this->getSignatureForLinkList()]);
        return $this->client->send($request);
    }

    private function buildGetAllLinksUrl(): string
    {
        // todo: find better way to build URL
        return 'https://onelink.pay1.de/api/v1/payment-links?' .
            'merchantId=' . $_ENV['PAYONE_MID'] .
            '&accountId=' . $_ENV['PAYONE_AID'] .
            '&portalId=' . $_ENV['PAYONE_PORTAL_ID'] .
            '&mode=' . $_ENV['PAYONE_MODE']; // .
        //'&page=0&limit=25';
    }

    private function getSignatureForLinkList(): string
    {
        return 'payone-hmac-sha256 ' . base64_encode(hash_hmac(
                'sha256',
                $_ENV['PAYONE_MID'] . $_ENV['PAYONE_AID'] . $_ENV['PAYONE_PORTAL_ID'] . $_ENV['PAYONE_MODE'],
                $_ENV['PAYONE_KEY'], true
            ));
    }

    public function getLink(string $linkId): ?Link
    {
        $request = new Request('GET', 'https://onelink.pay1.de/api/v1/payment-links/' . $linkId,
            ['Authorization' => $this->getSignatureForGetLink($linkId)]);
        $response = $this->client->send($request);
        if ($response->getStatusCode() !== 200) {
            return null;
        }
        return Link::fromResponse(json_decode($response->getBody(), true));

    }

    private function getSignatureForGetLink(string $linkId): string
    {
        return 'payone-hmac-sha256 ' . base64_encode(hash_hmac(
                'sha256',
                $linkId,
                $_ENV['PAYONE_KEY'],
                true
            ));
    }

    public function createLink(RequestInterface $slimRequest): ResponseInterface
    {
        $postData = (array)$slimRequest->getParsedBody();
        $amount = number_format($postData['amount'], 2, '', '');
        $body = [
            'merchantId' => $_ENV['PAYONE_MID'],
            'accountId' => $_ENV['PAYONE_AID'],
            'portalId' => $_ENV['PAYONE_PORTAL_ID'],
            'mode' => $_ENV['PAYONE_MODE'],
            'reference' => uniqid(),
            'amount' => $amount,
            'currency' => $postData['currency'],
            'shoppingCart' => [[
                'type' => 'goods',
                'number' => uniqid(),
                'price' => $amount,
                'quantity' => 1,
                'description' => 'Your order with Example Merchant',
                'totalAmount' => $amount,
                'vatRate' => 16
            ]],
            'intent' => 'authorization',
            'paymentMethods' => [
                'visa',
                'mastercard',
                'paypal',
                'sofort',
                'giropay',
                'paydirekt',
                'sepa'
            ],
            'billing' => [
                'firstName' => $postData['firstname'],
                'lastName' => $postData['lastname'],
                'country' => $postData['country'],
            ],
            'email' => $postData['email'],
        ];

        $request = new Request('POST',
            'https://onelink.pay1.de/api/v1/payment-links',
            [
                'Authorization' => $this->getBodySignatureForLinkCreation($body),
                'Content-type' => 'application/json'
            ],
            json_encode($body)
        );

        $response = $this->client->send($request, ['http_errors' => false]);


        return $response;
    }

    private function getBodySignatureForLinkCreation(array $body): string
    {
        // See https://docs.payone.com/display/public/PLATFORM/Authorization
        $fieldsToSign = [
            'merchantId',
            'accountId',
            'portalId',
            'mode',
            'reference',
            'amount',
            'currency'
        ];

        $dataToSign = '';
        foreach ($fieldsToSign as $field) {
            $dataToSign .= $body[$field];
        }
        // It is important to set the raw_output parameter to true
        // See https://docs.payone.com/display/public/PLATFORM/Hmac+SHA256+Code+Example#HmacSHA256CodeExample-PHP
        return 'payone-hmac-sha256 ' . base64_encode(hash_hmac('sha256', $dataToSign, $_ENV['PAYONE_KEY'], true));
    }
}