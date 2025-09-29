<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Akika\LaravelStanbic\Enums\PaymentMethod as PaymentMethodEnum;

class PaymentMethod extends XmlValueObject
{
    public function __construct(public PaymentMethodEnum $paymentMethod) {}

    public function getName(): string
    {
        return 'PmtMtd';
    }

    /** @return array<string, string> */
    public function getElement(): array
    {
        return [$this->getName() => $this->paymentMethod->value];
    }
}
