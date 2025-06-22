<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class CustomerCreditTransferInitiation
{
    /** @param array<string, mixed> $children */
    public function __construct(public readonly array $children) {}

    public function getName(): string
    {
        return 'CstmrCdtTrfInitn';
    }
}
