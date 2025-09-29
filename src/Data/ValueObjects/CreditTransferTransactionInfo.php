<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Akika\LaravelStanbic\Enums\Currency;
use ValueError;

class CreditTransferTransactionInfo extends XmlValueObject
{
    public ?PaymentId $paymentId = null;

    public ?Amount $amount = null;

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

    /**
     * @param  string  $memberId  The bank's branch code
     * @param  ?string  $name  The bank's name
     * @param  ?PostalAddress  $postalAddress  The bank's postal address
     */
    public function setCreditorAgent(
        string $memberId,
        ?string $name = null,
        ?PostalAddress $postalAddress = null,
    ): self {
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
