<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Akika\LaravelStanbic\Enums\ChargeBearerType;
use Akika\LaravelStanbic\Enums\Currency;
use Akika\LaravelStanbic\Enums\InstructionPriority;
use Akika\LaravelStanbic\Enums\PaymentMethod as PaymentMethodEnum;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use ValueError;

class PaymentInfo extends XmlValueObject
{
    public ?PaymentInfoId $paymentInfoId = null;

    public ?PaymentMethod $paymentMethod = null;

    public ?BatchBooking $batchBooking = null;

    public ?PaymentTypeInfo $paymentTypeInfo = null;

    public ?RequestedExecutionDate $requestedExecutionDate = null;

    public ?Debtor $debtor = null;

    public ?DebtorAccount $debtorAccount = null;

    public ?DebtorAgent $debtorAgent = null;

    public ?ChargeBearer $chargeBearer = null;

    /** @var Collection<int, CreditTransferTransactionInfo> */
    public Collection $creditTransferTransactionInfos;

    public function __construct()
    {
        $this->creditTransferTransactionInfos = collect();
    }

    public function getName(): string
    {
        return 'PmtInf';
    }

    /** @return array<string, mixed> */
    public function getElement(): array
    {
        if (
            ! $this->paymentInfoId ||
            ! $this->batchBooking ||
            ! $this->paymentTypeInfo ||
            ! $this->requestedExecutionDate ||
            ! $this->debtor ||
            ! $this->debtorAccount ||
            ! $this->chargeBearer ||
            ! $this->creditTransferTransactionInfos->count()
        ) {
            throw new ValueError;
        }

        $creditTransferTransactionInfoName = (new CreditTransferTransactionInfo)->getName();
        $creditTransferTransactionInfos = $this->creditTransferTransactionInfos
            ->map(fn (CreditTransferTransactionInfo $info) => $info->getElement())
            ->all();

        return [
            ...$this->paymentInfoId->getElement(),
            ...($this->paymentMethod?->getElement() ?? []),
            ...$this->batchBooking->getElement(),
            ...$this->paymentTypeInfo->getElement(),
            ...$this->requestedExecutionDate->getElement(),
            ...$this->debtor->getElement(),
            ...$this->debtorAccount->getElement(),
            ...($this->debtorAgent?->getElement() ?? []),
            ...$this->chargeBearer->getElement(),
            $creditTransferTransactionInfoName => $creditTransferTransactionInfos,
        ];
    }

    public static function make(): self
    {
        return new self;
    }

    /** @param string $id An alphanumeric id without any special chars */
    public function setPaymentInfoId(string $id): self
    {
        $this->paymentInfoId = new PaymentInfoId($id);

        return $this;
    }

    public function setPaymentMethod(PaymentMethodEnum $paymentMethod): self
    {
        $this->paymentMethod = new PaymentMethod($paymentMethod);

        return $this;
    }

    public function setBatchBooking(bool $enabled): self
    {
        $this->batchBooking = new BatchBooking($enabled);

        return $this;
    }

    public function setPaymentTypeInfo(InstructionPriority $instructionPriority, ?int $propietaryCategoryCode = null): self
    {
        $this->paymentTypeInfo = new PaymentTypeInfo($instructionPriority, $propietaryCategoryCode);

        return $this;
    }

    public function setRequestedExecutionDate(Carbon|CarbonImmutable $date): self
    {
        $this->requestedExecutionDate = new RequestedExecutionDate($date);

        return $this;
    }

    public function setDebtor(string $name, ?PostalAddress $postalAddress = null): self
    {
        $this->debtor = new Debtor($name, $postalAddress);

        return $this;
    }

    public function setDebtorAccount(string $id, Currency $currency): self
    {
        $this->debtorAccount = new DebtorAccount($id, $currency);

        return $this;
    }

    /**
     * @param  string  $memberId  The bank's branch code
     * @param  ?string  $name  The bank's name
     * @param  ?PostalAddress  $postalAddress  The bank's postal address
     */
    public function setDebtorAgent(
        string $memberId,
        ?string $name = null,
        ?PostalAddress $postalAddress = null,
    ): self {
        $this->debtorAgent = new DebtorAgent(
            new ClearingSystemMemberId($memberId),
            $name,
            $postalAddress,
        );

        return $this;
    }

    public function setChargeBearer(ChargeBearerType $chargeBearerType): self
    {
        $this->chargeBearer = new ChargeBearer($chargeBearerType);

        return $this;
    }

    public function addCreditTransferTransactionInfo(CreditTransferTransactionInfo $creditTransferTransactionInfo): self
    {
        $this->creditTransferTransactionInfos->push($creditTransferTransactionInfo);

        return $this;
    }

    public function getCreditTransferTransactionInfoTotals(): float
    {
        $sum = (float) $this->creditTransferTransactionInfos->sum(function (CreditTransferTransactionInfo $info) {
            return $info->amount->instructedAmount ?? 0;
        });

        return $sum;
    }
}
