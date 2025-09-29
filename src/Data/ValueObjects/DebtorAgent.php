<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class DebtorAgent extends XmlValueObject
{
    public function __construct(
        public ClearingSystemMemberId $clearingSystemMemberId,
        public ?string $name = null,
        public ?PostalAddress $postalAddress = null,
    ) {}

    public function getName(): string
    {
        return 'DbtrAgt';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        $body = [
            ...$this->clearingSystemMemberId->getElement(),
        ];

        if ($this->name) {
            $body['Nm'] = $this->name;
        }

        if ($this->postalAddress) {
            $body = [
                ...$body,
                ...$this->postalAddress->getElement(),
            ];
        }

        return [$this->getName() => [
            'FinInstnId' => $body,
        ]];
    }
}
