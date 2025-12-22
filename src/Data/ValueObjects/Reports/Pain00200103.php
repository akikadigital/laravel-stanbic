<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Illuminate\Support\Collection;
use Saloon\XmlWrangler\XmlReader;

/**
 * Customer payment status report
 */
class Pain00200103
{
    public GroupHeader $groupHeader;

    public OriginalGroupInfoAndStatus $originalGroupInfoAndStatus;

    /** @var Collection<int, OriginalPaymentInfoAndStatus> */
    public ?Collection $originalPaymentInfoAndStatuses;

    public function __construct(public XmlReader $xmlReader) {}

    public static function fromXml(string $xml): self
    {
        $report = new self(XmlReader::fromString($xml));

        $report->groupHeader = GroupHeader::fromXmlReader($report->xmlReader);
        $report->originalGroupInfoAndStatus = OriginalGroupInfoAndStatus::fromXmlReader($report->xmlReader);
        $report->originalPaymentInfoAndStatuses = OriginalPaymentInfoAndStatus::fromXmlReader($report->xmlReader);

        return $report;
    }

    /** @return Collection<int, string> */
    public function getAllStatusReasons(): Collection
    {
        $reasons = $this->originalGroupInfoAndStatus->statusReasonInfos->additionalInfos;

        $this->originalPaymentInfoAndStatuses?->each(function (OriginalPaymentInfoAndStatus $originalPaymentInfoAndStatus) use (&$reasons) {
            $reasons = $reasons->merge($originalPaymentInfoAndStatus->statusReasonInfos->additionalInfos);

            $originalPaymentInfoAndStatus
                ->transactionInfoAndStatuses
                ->each(function (TransactionInfoAndStatus $transactionInfoAndStatus) use (&$reasons) {
                    $reasons = $reasons->merge($transactionInfoAndStatus->statusReasonInfos->additionalInfos);
                });
        });

        return $reasons;
    }
}
