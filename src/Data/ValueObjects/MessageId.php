<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class MessageId extends XmlValueObject
{
    public function __construct(public readonly string $id) {}

    public function getName(): string
    {
        return 'MsgId';
    }

    /** @return array<string, string> */
    public function getElement(): array
    {
        return [$this->getName() => $this->id];
    }
}
