<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Carbon\Carbon;
use Carbon\CarbonImmutable;

class CreationDateTime extends XmlValueObject
{
    public function __construct(public Carbon|CarbonImmutable $dateTime) {}

    public function getName(): string
    {
        return 'CreDtTm';
    }

    /** @return array<string, string> */
    public function getElement(): array
    {
        return [$this->getName() => $this->dateTime->toDateTimeLocalString()];
    }
}
