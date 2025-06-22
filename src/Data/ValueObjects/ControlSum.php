<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class ControlSum extends XmlValueObject
{
    public function __construct(public readonly float $sum) {}

    public function getName(): string
    {
        return 'CtrlSum';
    }

    /** @return array<string, float> */
    public function getElement(): array
    {
        return [$this->getName() => $this->sum];
    }
}
