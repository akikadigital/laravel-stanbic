<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class PaymentInfo extends XmlValueObject
{
    public function __construct(
        public PaymentInfoId $paymentInfoId,
        public BatchBooking $batchBooking,
        public PaymentTypeInfo $paymentTypeInfo,
        public RequestedExecutionDate $requestedExecutionDate,
        public Debtor $debtor,
        public DebtorAccount $debtorAccount,
        public CreditTransferTransactionInfo $creditTransferTransactionInfo,
    ) {}

    public function getName(): string
    {
        return 'CdtTrfTxInf';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            ...$this->paymentInfoId->getElement(),
            ...$this->batchBooking->getElement(),
            ...$this->paymentTypeInfo->getElement(),
            ...$this->requestedExecutionDate->getElement(),
            ...$this->debtor->getElement(),
            ...$this->debtorAccount->getElement(),
            ...$this->creditTransferTransactionInfo->getElement(),
        ]];
    }
}
