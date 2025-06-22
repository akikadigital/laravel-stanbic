<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

abstract class XmlValueObject
{
    abstract public function getName(): string;

    /** @return array<string, string>|null */
    public function getAttributes(): ?array
    {
        return null;
    }
}
