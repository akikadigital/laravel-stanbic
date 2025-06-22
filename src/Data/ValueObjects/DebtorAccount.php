<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Akika\LaravelStanbic\Enums\Currency;

class DebtorAccount extends XmlValueObject
{
    public function __construct(public string $id, public Currency $currency) {}

    public function getName(): string
    {
        return 'DbtrAcct';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            'Id' => [
                'Othr' => [
                    'id' => $this->id,
                ],
            ],
            'Ccy' => $this->currency->value,
        ]];
    }
}
