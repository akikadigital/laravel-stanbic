<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Carbon\Carbon;

class RequestedExecutionDate extends XmlValueObject
{
    public function __construct(public readonly Carbon $date) {}

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
