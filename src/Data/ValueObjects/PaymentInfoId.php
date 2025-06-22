<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class PaymentInfoId extends XmlValueObject
{
    public function __construct(public readonly string $id) {}

    public function getName(): string
    {
        return 'PmtInfId';
    }

    /** @return array<string, string> */
    public function getElement(): array
    {
        return [$this->getName() => $this->id];
    }
}
