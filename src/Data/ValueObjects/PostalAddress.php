<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Akika\LaravelStanbic\Enums\CountryCode;

class PostalAddress extends XmlValueObject
{
    public function __construct(
        public ?string $streetName = null,
        public ?string $buildingNumber = null,
        public ?string $postalCode = null,
        public ?string $townName = null,
        public ?CountryCode $countryCode = null
    ) {}

    public function getName(): string
    {
        return 'PstlAdr';
    }

    /** @return array<string, array<string, string>> */
    public function getElement(): array
    {
        return [$this->getName() => $this->getPostalAddress()];
    }

    /** @return array<string, string> */
    public function getPostalAddress(): array
    {
        $data = [];
        if ($this->streetName) {
            $data['StrtNm'] = $this->streetName;
        }

        if ($this->buildingNumber) {
            $data['BldgNb'] = $this->buildingNumber;
        }

        if ($this->postalCode) {
            $data['PstCd'] = $this->postalCode;
        }

        if ($this->townName) {
            $data['TwnNm'] = $this->townName;
        }

        if ($this->countryCode) {
            $data['Ctry'] = $this->countryCode->value;
        }

        return $data;
    }
}
