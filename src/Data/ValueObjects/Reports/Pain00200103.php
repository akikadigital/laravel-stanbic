<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Enums\GroupStatusType;
use Saloon\XmlWrangler\XmlReader;

/**
 * Customer payment status report
 */
class Pain00200103
{
    public OriginalGroupInfoAndStatus $originalGroupInfoAndStatus;

    public function __construct(public XmlReader $xmlReader) {}

    public static function fromXml(string $xml): self
    {
        $report = new self(XmlReader::fromString($xml));

        /** @var array<string, string> $groupInfoAndStatus */
        $groupInfoAndStatus = $report->xmlReader->value('CstmrPmtStsRpt.OrgnlGrpInfAndSts')->sole();
        $report->originalGroupInfoAndStatus = OriginalGroupInfoAndStatus::fromArray($groupInfoAndStatus);

        return $report;
    }

    public function getGroupStatus(): GroupStatusType
    {
        return $this->originalGroupInfoAndStatus->groupStatus;
    }
}
