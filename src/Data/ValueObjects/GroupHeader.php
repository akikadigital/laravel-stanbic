<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use ValueError;

class GroupHeader extends XmlValueObject
{
    public ?ControlSum $controlSum;

    public function __construct() {}

    public function getName(): string
    {
        return 'GrpHdr';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        if (! $this->controlSum) {
            throw new ValueError;
        }

        return [
            $this->getName() => [
                ...$this->controlSum->getElement(),
            ],
        ];
    }
}
