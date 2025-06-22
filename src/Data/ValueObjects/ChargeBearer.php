<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Akika\LaravelStanbic\Enums\ChargeBearerType;

/**
 * Indicates which particular party will be responsible for bearing the charges for the transaction.
 * CRED: Counter party bears all costs
 * DEBT: Ordering customer bears all costs
 * SHAR: Transaction charges on the sender side are to be borne by the Ordering Customer,
 * transaction charges on the receiver side are to be borne by the Counter Party.
 *
 * For ICM refer to sheet: ICM rules, rule : 1.5 Charges indicator
 */
class ChargeBearer extends XmlValueObject
{
    public function __construct(public ChargeBearerType $bearerType) {}

    public function getName(): string
    {
        return 'ChrgBr';
    }

    /** @return array<string, string> */
    public function getElement(): array
    {
        return [$this->getName() => $this->bearerType->value];
    }
}
