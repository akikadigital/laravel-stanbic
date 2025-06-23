<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Enums\GroupStatusType;
use Carbon\Carbon;
use Saloon\XmlWrangler\XmlReader;

/**
 * Customer payment status report
 */
class Pain00200103
{
    public OriginalGroupInfoAndStatus $originalGroupInfoAndStatus;

    public GroupHeader $groupHeader;

    public function __construct(public XmlReader $xmlReader) {}

    public static function fromXml(string $xml): self
    {
        $report = new self(XmlReader::fromString($xml));

        $report->setOriginalGroupInfoAndStatus();
        $report->setGroupHeader();

        return $report;
    }

    public function setGroupHeader(): void
    {
        /** @var string $messageId */
        $messageId = $this->xmlReader->value('CstmrPmtStsRpt.GrpHdr.MsgId')->sole();
        /** @var string $creditDateTime */
        $creditDateTime = $this->xmlReader->value('CstmrPmtStsRpt.GrpHdr.creditDateTime')->sole();
        /** @var string $initiatingPartyName */
        $initiatingPartyName = $this->xmlReader->value('CstmrPmtStsRpt.GrpHdr.InitgPty.Nm')->sole();
        /** @var string $initiatingPartyBicOrBei */
        $initiatingPartyBicOrBei = $this->xmlReader->value('CstmrPmtStsRpt.GrpHdr.InitgPty.Id.OrgId.BICOrBEI')->sole();

        $this->groupHeader = new GroupHeader(
            $messageId,
            Carbon::parse($creditDateTime),
            $initiatingPartyName,
            $initiatingPartyBicOrBei,
        );
    }

    public function setOriginalGroupInfoAndStatus(): void
    {
        /** @var array<string, string> $groupInfoAndStatus */
        $groupInfoAndStatus = $this->xmlReader->value('CstmrPmtStsRpt.OrgnlGrpInfAndSts')->sole();
        $this->originalGroupInfoAndStatus = OriginalGroupInfoAndStatus::fromArray($groupInfoAndStatus);
    }

    public function getGroupStatus(): GroupStatusType
    {
        return $this->originalGroupInfoAndStatus->groupStatus;
    }
}
