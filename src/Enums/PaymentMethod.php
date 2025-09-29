<?php

namespace Akika\LaravelStanbic\Enums;

enum PaymentMethod: string
{
    case DirectDebit = 'DD';
    case CreditTransfer = 'TRF';
    case TransferAdvise = 'TRA';
    case Cheque = 'CHK';
}
