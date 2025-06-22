<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Akika\LaravelStanbic\Enums\Currency;
use Saloon\XmlWrangler\Data\Element;

class Amount extends XmlValueObject
{
    public function __construct(public string $instructedAmount, public Currency $currency) {}

    public function getName(): string
    {
        return 'Amt';
    }

    /** @return array<string, string> */
    public function getAttributes(): array
    {
        return [
            'Ccy' => $this->currency->value,
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            'InstdAmt' => [
                Element::make()->setAttributes($this->getAttributes()),
            ],
        ]];
    }
}
