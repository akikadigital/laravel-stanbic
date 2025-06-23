<?php

namespace Akika\LaravelStanbic\Enums;

enum TransactionStatusType: string
{
    case Acsp = 'ACSP';
    case Pdng = 'PDNG';
    case Rjct = 'RJCT';
    case Acwc = 'ACWC';

    public function getDescription(): string
    {
        return match ($this) {
            self::Acsp => 'The transaction has been processed successfully',
            self::Pdng => 'The transaction has been passed the business validation and pending for further business processing',
            self::Rjct => 'The transaction has been rejected',
            self::Acwc => 'The transaction is accepted but a change will be made, such as date or remittance not sent'
        };
    }
}
