<?php

namespace Fbender\Payonelink\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


class PayoneLinkService
{
    private int $mid;
    private int $aid;
    private int $portalid;
    private string $key;
    private string $mode;
    private Client $client;

    public function __construct(Client $client)
    {
        $this->aid = $_ENV['PAYONE_AID'];
        $this->mid = $_ENV['PAYONE_MID'];
        $this->portalid = $_ENV['PAYONE_PORTAL_ID'];
        $this->key = $_ENV['PAYONE_KEY'];
        $this->mode = $_ENV['PAYONE_MODE'];
        $this->client = $client;
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
            'currency' => 'EUR',
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
                'Authorization' => $this->getBodySignature($body),
                'Content-type' => 'application/json'
            ],
            json_encode($body)
        );

        return $this->client->send($request, ['http_errors' => false]);
    }

    private function getBodySignature(array $body): string
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