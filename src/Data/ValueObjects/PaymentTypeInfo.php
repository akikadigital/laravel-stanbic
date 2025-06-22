<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Akika\LaravelStanbic\Enums\InstructionPriority;

/**
 * Based on whether priority processing vs. normal processing is offered by the bank.
 * Normal OR Urgent (RTGS/RTC)
 * Must be NORM for ENCR Payments in Namibia.
 */
class PaymentTypeInfo extends XmlValueObject
{
    public function __construct(public readonly InstructionPriority $instructionPriority, public readonly int $propietaryCategoryCode) {}

    public function getName(): string
    {
        return 'PmtTpInf';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            'InstrPrty' => $this->instructionPriority->value,
            'CtgyPurp' => [                                     // Category purpose of the payment
                'Prtry' => $this->propietaryCategoryCode,
            ],
        ]];
    }
}
