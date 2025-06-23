<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class CustomerCreditTransferInitiation
{
    public function __construct(public GroupHeader $groupHeader, public PaymentInfo $paymentInfo) {}

    public function getName(): string
    {
        return 'CstmrCdtTrfInitn';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            ...$this->groupHeader->getElement(),
            ...$this->paymentInfo->getElement(),
        ]];
    }
}
