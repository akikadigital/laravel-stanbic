<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class InitiatingParty extends XmlValueObject
{
    /**
     * @param  string  $name  Name of the initiating party
     * @param  string  $id  Identification of the initiating party
     */
    public function __construct(public ?string $name, public ?string $id) {}

    public function getName(): string
    {
        return 'InitgPty';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        $body = [];
        if ($this->name) {
            $body['Nm'] = $this->name;
        }

        if ($this->id) {
            $body['Id'] = ['OrgId' => [
                'Othr' => [
                    'Id' => $this->id,
                ],
            ]];
        }

        return [$this->getName() => $body];
    }
}
