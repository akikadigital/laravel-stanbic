<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class NumberOfTransactions extends XmlValueObject
{
    public function __construct(public readonly int $count) {}

    public function getName(): string
    {
        return 'NbOfTxs';
    }

    /** @return array<string, int> */
    public function getElement(): array
    {
        return [$this->getName() => $this->count];
    }
}
