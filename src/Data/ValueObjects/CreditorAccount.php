<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class CreditorAccount extends XmlValueObject
{
    public function __construct(
        public string $id,
    ) {}

    public function getName(): string
    {
        return 'CdtrAcct';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            'Id' => [
                'Othr' => [
                    'Id' => $this->id,
                ],
            ],
        ]];
    }
}
