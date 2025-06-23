<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class CreditTransferTransactionInfo extends XmlValueObject
{
    public function __construct(
        public PaymentId $paymentId,
        public Amount $amount,
        public ChargeBearer $chargeBearer,
        public CreditorAgent $creditorAgent,
        public Creditor $creditor,
        public CreditorAccount $creditorAccount,
        public RemittanceInfo $remittanceInfo,
    ) {}

    public function getName(): string
    {
        return 'CdtTrfTxInf';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            ...$this->paymentId->getElement(),
            ...$this->amount->getElement(),
            ...$this->chargeBearer->getElement(),
            ...$this->creditorAgent->getElement(),
            ...$this->creditor->getElement(),
            ...$this->creditorAccount->getElement(),
            ...$this->remittanceInfo->getElement(),
        ]];
    }
}
