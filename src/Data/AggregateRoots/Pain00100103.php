<?php

namespace Akika\LaravelStanbic\Data\AggregateRoots;

use Akika\LaravelStanbic\Data\ValueObjects\CustomerCreditTransferInitiation;
use Akika\LaravelStanbic\Data\ValueObjects\Document;
use Akika\LaravelStanbic\Data\ValueObjects\GroupHeader;
use Akika\LaravelStanbic\Data\ValueObjects\PaymentInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Saloon\XmlWrangler\XmlWriter;
use ValueError;

/**
 * Initiates a customer credit transfer
 */
class Pain00100103
{
    private string $xmlns = 'urn:iso:std:iso:20022:tech:xsd:pain.001.001.03';

    private string $xmlnsXsi = 'http://www.w3.org/2001/XMLSchema-instance';

    public ?CustomerCreditTransferInitiation $customerCreditTransferInitiation = null;

    public ?GroupHeader $groupHeader = null;

    /** @var Collection<int, PaymentInfo> */
    public Collection $paymentInfos;

    public function __construct()
    {
        $this->paymentInfos = collect();
    }

    public function build(): string
    {
        if (! $this->groupHeader || ! $this->paymentInfos->count()) {
            throw new ValueError;
        }

        $this->computeGroupHeaderTotals();

        /** @var GroupHeader $groupHeader */
        $groupHeader = $this->groupHeader;
        $this->customerCreditTransferInitiation = new CustomerCreditTransferInitiation($groupHeader, $this->paymentInfos);

        $document = new Document($this->xmlns, $this->xmlnsXsi);

        return (new XmlWriter)->write(
            $document->getElement(),
            $this->customerCreditTransferInitiation->getElement(),
        );
    }

    public static function make(): self
    {
        return new self;
    }

    public function setGroupHeader(GroupHeader $groupHeader): self
    {
        $this->groupHeader = $groupHeader;

        return $this;
    }

    public function computeGroupHeaderTotals(): void
    {
        if ($this->groupHeader == null || ! $this->paymentInfos->count()) {
            return;
        }

        // This accounts for multiple PmtInf with multiple CdtTrfTxInf in them
        $controlSum = $this->paymentInfos->sum(function (PaymentInfo $paymentInfo) {
            return $paymentInfo->getCreditTransferTransactionInfoTotals();
        });

        // This too accounts for multiple PmtInf with multiple CdtTrfTxInf in them
        $numberOfTransactions = $this->paymentInfos->sum(function (PaymentInfo $paymentInfo) {
            return $paymentInfo->creditTransferTransactionInfos->count();
        });

        $this->groupHeader->setControlSum($controlSum);
        $this->groupHeader->setNumberOfTransactions($numberOfTransactions);
    }

    public function addPaymentInfo(PaymentInfo $paymentInfo): self
    {
        $this->paymentInfos->push($paymentInfo);

        return $this;
    }

    public function store(?string $path = null): ?string
    {
        /** @var string */
        $disk = config('stanbic.disk');

        /** @var string */
        $prefix = config('stanbic.output_file_prefix');

        /** @var string */
        $root = config('stanbic.output_root');

        // CINCH_CINCHH2H_Pain001v3_GH_TST_yyyymmddhhmmssSSS.xml
        // Microseconds (u) has 6 chars, we need only 3 (SSS). so strip the last 3 chars
        $filename = substr(now()->format('YmdHisu'), 0, -3);

        $path ??= "{$prefix}{$filename}.xml";

        if (! Storage::disk($disk)->put("{$root}/{$path}", $this->build())) {
            return null;
        }

        return $path;
    }
}
