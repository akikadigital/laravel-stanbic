<?php

namespace Akika\LaravelStanbic\Enums;

enum GroupStatusType: string
{
    case Acsp = 'ACSP';
    case Part = 'PART';
    case Pdng = 'PDNG';
    case Rjct = 'RJCT';
    case Rcvd = 'RCVD';

    public function getDescription(): string
    {
        return match ($this) {
            self::Acsp => 'All the transactions have been processed successfully by Standard Bank',
            self::Part => 'Some of the transactions in this report have been rejected by Standard Bank',
            self::Pdng => 'All the transactions in this report have passed validation and are being processed by the bank',
            self::Rjct => 'All the transactions in this report have been rejected by the Standard Bank or by external agents such as clearing houses',
            self::Rcvd => 'Your file has been received'
        };
    }
}
