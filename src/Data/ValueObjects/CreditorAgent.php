<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Akika\LaravelStanbic\Enums\CountryCode;

/**
 * Receiving bank
 */
class CreditorAgent extends XmlValueObject
{
    public function __construct(
        public ClearingSystemMemberId $clearingSystemMemberId,
        public string $name,
        public CountryCode $countryCode
    ) {}

    public function getName(): string
    {
        return 'CdtrAgt';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            'FinInstnId' => [
                ...$this->clearingSystemMemberId->getElement(),
                'Nm' => $this->name,
                'PstlAdr' => [                                  // Postal Address
                    'Ctry' => $this->countryCode->value,        // Country
                ],
            ],
        ]];
    }
}
