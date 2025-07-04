<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

/**
 * Receiving bank
 */
class CreditorAgent extends XmlValueObject
{
    public function __construct(
        public ClearingSystemMemberId $clearingSystemMemberId,
        public string $name,
        public PostalAddress $postalAddress
    ) {}

    public function getName(): string
    {
        return 'CdtrAgt';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            'FinInstnId' => [
                ...$this->clearingSystemMemberId->getElement(),
                'Nm' => $this->name,
                ...$this->postalAddress->getElement(),
            ],
        ]];
    }
}
