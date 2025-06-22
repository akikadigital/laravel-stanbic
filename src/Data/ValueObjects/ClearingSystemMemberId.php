<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class ClearingSystemMemberId extends XmlValueObject
{
    public function __construct(public string $memberId) {}

    public function getName(): string
    {
        return 'ClrSysMmbId';
    }

    /** @return array<string, array<string, string>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            'MmbId' => $this->memberId,
        ]];
    }
}
