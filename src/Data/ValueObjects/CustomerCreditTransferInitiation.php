<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Illuminate\Support\Collection;

class CustomerCreditTransferInitiation
{
    /** @param Collection<int, PaymentInfo> $paymentInfos */
    public function __construct(
        public GroupHeader $groupHeader,
        public Collection $paymentInfos
    ) {}

    public function getName(): string
    {
        return 'CstmrCdtTrfInitn';
    }

    /** @return array<string, array<string|int, mixed>> */
    public function getElement(): array
    {
        $paymentInfoName = (new PaymentInfo)->getName();
        $paymentInfos = $this->paymentInfos
            ->map(fn (PaymentInfo $paymentInfo) => $paymentInfo->getElement())
            ->all();

        return [$this->getName() => [
            ...$this->groupHeader->getElement(),
            $paymentInfoName => $paymentInfos,
        ]];
    }
}
