<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Carbon\Carbon;
use Carbon\CarbonImmutable;

class RequestedExecutionDate extends XmlValueObject
{
    public function __construct(public readonly Carbon|CarbonImmutable $date) {}

    public function getName(): string
    {
        return 'ReqdExctnDt';
    }

    /** @return array<string, string> */
    public function getElement(): array
    {
        return [$this->getName() => $this->date->toDateString()];
    }
}
