<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

/**
 * Identifies whether a single entry per individual transaction should be reflected on the statement (Itemized),
 * or
 * whether a batch entry reflecting the sum of the amounts of all transactions within the instruction should appear on the statement (Consolidated).
 * TRUE: Consolidated Statement Posting
 * FALSE: Itemized Statement Posting
 *
 * Please Note: Consolidated Posting is dependent upon payment type and clearing system processing.
 */
class BatchBooking extends XmlValueObject
{
    public function __construct(public readonly bool $enabled) {}

    public function getName(): string
    {
        return 'PmtInfId';
    }

    /** @return array<string, bool> */
    public function getElement(): array
    {
        return [$this->getName() => $this->enabled];
    }
}
