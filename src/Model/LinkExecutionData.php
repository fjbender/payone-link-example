<?php

namespace Fbender\Payonelink\Model;

use Spatie\DataTransferObject\DataTransferObject;

class LinkExecutionData extends DataTransferObject
{
    public string $linkId;
    public string $paymentProcess;
    public string $executionStatus;
    public string $paymentMethod;
    public string $executionTime;

    public static function fromLinkExecutionData(array $linkExecutionData): self
    {
        return new self ([
            'linkId' => $linkExecutionData['linkId'],
            'paymentProcess' => $linkExecutionData['paymentProcess'],
            'executionStatus' => $linkExecutionData['executionStatus'],
            'paymentMethod' => $linkExecutionData['paymentMethod'],
            'executionTime' => $linkExecutionData['executionTime']
        ]);
    }
}