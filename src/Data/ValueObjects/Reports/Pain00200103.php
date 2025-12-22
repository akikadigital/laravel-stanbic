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

    public ?OriginalPaymentInfoAndStatus $originalPaymentInfoAndStatus;

    public function __construct(public XmlReader $xmlReader) {}

    public static function fromXml(string $xml): self
    {
        $report = new self(XmlReader::fromString($xml));

        $report->groupHeader = GroupHeader::fromXmlReader($report->xmlReader);
        $report->originalGroupInfoAndStatus = OriginalGroupInfoAndStatus::fromXmlReader($report->xmlReader);
        $report->originalPaymentInfoAndStatus = OriginalPaymentInfoAndStatus::fromXmlReader($report->xmlReader);

        return $report;
    }

    /** @return Collection<int, string> */
    public function getAllStatusReasons(): Collection
    {
        $reasons = $this->originalGroupInfoAndStatus->statusReasonInfos->additionalInfos;
        if ($this->originalPaymentInfoAndStatus) {
            $reasons = $reasons->merge($this->originalPaymentInfoAndStatus->statusReasonInfos->additionalInfos);
            $reasons = $reasons->merge($this->originalPaymentInfoAndStatus->transactionInfoAndStatus->statusReasonInfos->additionalInfos);
        }

        return $reasons;
    }
}
