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

    public function __construct(public string $xml)
    {
        $xmlReader = XmlReader::fromString($xml);
        $this->groupHeader = GroupHeader::fromXmlReader($xmlReader);
        $this->originalGroupInfoAndStatus = OriginalGroupInfoAndStatus::fromXmlReader($xmlReader);
        $this->originalPaymentInfoAndStatuses = OriginalPaymentInfoAndStatus::fromXmlReader($xmlReader);
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
