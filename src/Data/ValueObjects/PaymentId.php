<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class PaymentId extends XmlValueObject
{
    public function __construct(public string $instructionId, public string $endToEndId) {}

    public function getName(): string
    {
        return 'PmtId';
    }

    /** @return array<string, array<string, string>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            'InstrId' => $this->instructionId,
            'EndToEndId' => $this->endToEndId,
        ]];
    }
}
