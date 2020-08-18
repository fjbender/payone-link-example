<?php

namespace Fbender\Payonelink\Model;

use Doctrine\ORM\Mapping as ORM;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * @ORM\Entity
 * @ORM\Table(name="link")
 */
class Link extends DataTransferObject
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected int $id;
    /**
     * @ORM\Column(type="string")
     */
    public string $linkId;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public string $firstname;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public string $lastname;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public int $amount;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public string $currency;
    /**
     * @ORM\Column(type="string")
     */
    public string $rawResponse;

    public static function fromResponse(array $responseBody): self
    {
        return new self([
            'firstname' => $responseBody['billing']['firstName'],
            'lastname' => $responseBody['billing']['lastName'],
            'linkId' => $responseBody['id'],
            'amount' => $responseBody['amount'],
            'currency' => $responseBody['currency'],
            'rawResponse' => json_encode($responseBody)
        ]);
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