<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class Creditor extends XmlValueObject
{
    public function __construct(
        public string $name,
        public ?PostalAddress $postalAddress = null,
    ) {}

    public function getName(): string
    {
        return 'Cdtr';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        $body = ['Nm' => $this->name];

        if ($this->postalAddress) {
            $body = [
                ...$body,
                ...$this->postalAddress->getElement(),
            ];
        }

        return [$this->getName() => $body];
    }
}
