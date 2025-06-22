<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class Debtor extends XmlValueObject
{
    public function __construct(public string $name) {}

    public function getName(): string
    {
        return 'Dbtr';
    }

    /** @return array<string, array<string, string>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            'Nm' => $this->name,
        ]];
    }
}
