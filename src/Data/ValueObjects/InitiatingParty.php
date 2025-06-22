<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class InitiatingParty extends XmlValueObject
{
    /**
     * @param  string  $name  Name of the initiating party
     * @param  string  $id  Identification of the initiating party
     */
    public function __construct(public string $name, public string $id) {}

    public function getName(): string
    {
        return 'InitgPty';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            'Nm' => $this->name,
            'OrgId' => [
                'Othr' => [
                    'Id' => $this->id,
                ],
            ],
        ]];
    }
}
