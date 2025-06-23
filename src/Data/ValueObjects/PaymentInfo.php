<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Akika\LaravelStanbic\Enums\Currency;
use Akika\LaravelStanbic\Enums\InstructionPriority;
use Carbon\Carbon;
use ValueError;

class PaymentInfo extends XmlValueObject
{
    public ?PaymentInfoId $paymentInfoId = null;

    public ?BatchBooking $batchBooking = null;

    public ?PaymentTypeInfo $paymentTypeInfo = null;

    public ?RequestedExecutionDate $requestedExecutionDate = null;

    public ?Debtor $debtor = null;

    public ?DebtorAccount $debtorAccount = null;

    public ?CreditTransferTransactionInfo $creditTransferTransactionInfo = null;

    public function getName(): string
    {
        return 'PmtInf';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        if (
            ! $this->paymentInfoId ||
            ! $this->batchBooking ||
            ! $this->paymentTypeInfo ||
            ! $this->requestedExecutionDate ||
            ! $this->debtor ||
            ! $this->debtorAccount
        ) {
            throw new ValueError;
        }

        return [$this->getName() => [
            ...$this->paymentInfoId->getElement(),
            ...$this->batchBooking->getElement(),
            ...$this->paymentTypeInfo->getElement(),
            ...$this->requestedExecutionDate->getElement(),
            ...$this->debtor->getElement(),
            ...$this->debtorAccount->getElement(),
            // ...$this->creditTransferTransactionInfo->getElement(),
        ]];
    }

    public static function make(): self
    {
        return new self;
    }

    public function setPaymentInfoId(string $id): self
    {
        $this->paymentInfoId = new PaymentInfoId($id);

        return $this;
    }

    public function setBatchBooking(bool $enabled): self
    {
        $this->batchBooking = new BatchBooking($enabled);

        return $this;
    }

    public function setPaymentTypeInfo(InstructionPriority $instructionPriority, int $propietaryCategoryCode): self
    {
        $this->paymentTypeInfo = new PaymentTypeInfo($instructionPriority, $propietaryCategoryCode);

        return $this;
    }

    public function setRequestedExecutionDate(Carbon $date): self
    {
        $this->requestedExecutionDate = new RequestedExecutionDate($date);

        return $this;
    }

    public function setDebtor(string $name): self
    {
        $this->debtor = new Debtor($name);

        return $this;
    }

    public function setDebtorAccount(string $id, Currency $currency): self
    {
        $this->debtorAccount = new DebtorAccount($id, $currency);

        return $this;
    }

    public function setCreditTransferTransactionInfo(CreditTransferTransactionInfo $creditTransferTransactionInfo): self
    {
        $this->creditTransferTransactionInfo = $creditTransferTransactionInfo;

        return $this;
    }
}
