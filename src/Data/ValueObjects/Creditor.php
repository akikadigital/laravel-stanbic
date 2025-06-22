<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class Creditor extends XmlValueObject
{
    public function __construct(
        public string $name,
        public PostalAddress $postalAddress,
    ) {}

    public function getName(): string
    {
        return 'Cdtr';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            'FinInstnId' => [
                'Nm' => $this->name,
                ...$this->postalAddress->getElement(),
            ],
        ]];
    }
}
