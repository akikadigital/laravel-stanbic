<?php

namespace Akika\LaravelStanbic\Enums;

/**
 * ACSP All the transactions have been processed successfully by Standard Bank
 * PART Some of the transactions in this report have been rejected by Standard Bank
 * PDNG All the transactions in this report have passed validation and are being processed by the bank.
 * RJCT All the transactions in this report have been rejected by the Standard Bank or by external agents such as clearing houses.
 * RCVD For the successfully received file.
 */
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
            self::Rcvd => 'For the successfully received file'
        };
    }
}
