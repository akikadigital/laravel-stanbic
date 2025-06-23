<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Akika\LaravelStanbic\Enums\ChargeBearerType;
use Akika\LaravelStanbic\Enums\Currency;
use ValueError;

class CreditTransferTransactionInfo extends XmlValueObject
{
    public ?PaymentId $paymentId = null;

    public ?Amount $amount = null;

    public ?ChargeBearer $chargeBearer = null;

    public ?CreditorAgent $creditorAgent = null;

    public ?Creditor $creditor = null;

    public ?CreditorAccount $creditorAccount = null;

    public ?RemittanceInfo $remittanceInfo = null;

    public function getName(): string
    {
        return 'CdtTrfTxInf';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        if (
            ! $this->paymentId ||
            ! $this->amount ||
            ! $this->chargeBearer ||
            ! $this->creditorAgent ||
            ! $this->creditor ||
            ! $this->creditorAccount ||
            ! $this->remittanceInfo
        ) {
            throw new ValueError;
        }

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

    public static function make(): self
    {
        return new self;
    }

    public function setPaymentId(string $instructionId, string $endToEndId): self
    {
        $this->paymentId = new PaymentId($instructionId, $endToEndId);

        return $this;
    }

    public function setAmount(int $amount, Currency $currency): self
    {
        $this->amount = new Amount($amount, $currency);

        return $this;
    }

    public function setChargeBearer(ChargeBearerType $chargeBearerType): self
    {
        $this->chargeBearer = new ChargeBearer($chargeBearerType);

        return $this;
    }

    public function setCreditorAgent(string $memberId, string $name, PostalAddress $postalAddress): self
    {
        $this->creditorAgent = new CreditorAgent(
            new ClearingSystemMemberId($memberId),
            $name,
            $postalAddress
        );

        return $this;
    }

    public function setCreditor(string $name, PostalAddress $postalAddress): self
    {
        $this->creditor = new Creditor($name, $postalAddress);

        return $this;
    }

    public function setCreditorAccount(string $id): self
    {
        $this->creditorAccount = new CreditorAccount($id);

        return $this;
    }

    public function setRemittanceInfo(string $unstructuredInfo): self
    {
        $this->remittanceInfo = new RemittanceInfo($unstructuredInfo);

        return $this;
    }
}
