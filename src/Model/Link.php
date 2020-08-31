<?php

namespace Fbender\Payonelink\Model;

use Spatie\DataTransferObject\DataTransferObject;

class Link extends DataTransferObject
{
    protected int $id;
    public string $linkId;
    public ?string $firstname;
    public string $lastname;
    public int $amount;
    public string $currency;
    public ?string $link;
    public string $rawResponse;
    public string $status;
    public ?string $paymentMethod;
    public ?string $paymentProcess;

    public static function fromResponse(array $responseBody): self
    {
        return new self([
            'firstname' => $responseBody['billing']['firstName'] ?? null,
            'lastname' => $responseBody['billing']['lastName'],
            'linkId' => $responseBody['id'],
            'amount' => $responseBody['amount'],
            'currency' => $responseBody['currency'],
            'link' => $responseBody['link'] ?? null, // link can be optional if not enough data is available for execution
            'status' => $responseBody['status'],
            'paymentMethod' => $responseBody['paymentMethod'] ?? null,
            'paymentProcess' => $responseBody['paymentProcess'] ?? null,
            'rawResponse' => json_encode($responseBody)
        ]);
    }

    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getLinkId(): string
    {
        return $this->linkId;
    }

    public function setLinkId(string $linkId): void
    {
        $this->linkId = $linkId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }

    public function setRawResponse(string $rawResponse): void
    {
        $this->rawResponse = $rawResponse;
    }
}