<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Enums\GroupStatusType;
use Carbon\Carbon;
use Saloon\XmlWrangler\XmlReader;

class OriginalGroupInfoAndStatus
{
    public function __construct(
        public string $originalMessageId,
        public string $originalMessageNameId,
        public Carbon $originalCreditDateTime,
        public int $originalNumberOfTransactions,
        public float $originalControlSum,
        public GroupStatusType $groupStatus,
        public StatusReasonInfos $statusReasonInfos
    ) {}

    public static function fromXmlReader(XmlReader $reader): self
    {
        $root = '//CstmrPmtStsRpt/OrgnlGrpInfAndSts';

        /** @var string */
        $originalMessageId = $reader->xpathValue("{$root}/OrgnlMsgId")->sole();

        /** @var string */
        $originalMessageNameId = $reader->xpathValue("{$root}/OrgnlMsgNmId")->sole();

        /** @var string */
        $originalCreditDateTime = $reader->xpathValue("{$root}/OrgnlCreDtTm")->sole();

        /** @var string */
        $originalNumberOfTransactions = $reader->xpathValue("{$root}/OrgnlNbOfTxs")->sole();

        /** @var string */
        $originalControlSum = $reader->xpathValue("{$root}/OrgnlCtrlSum")->sole();

        /** @var string */
        $groupStatus = $reader->xpathValue("{$root}/GrpSts")->sole();

        $additionalStatusInfo = StatusReasonInfos::fromXmlReader($reader, $root);

        return new self(
            $originalMessageId,
            $originalMessageNameId,
            Carbon::parse($originalCreditDateTime),
            (int) $originalNumberOfTransactions,
            (float) $originalControlSum,
            GroupStatusType::from($groupStatus),
            $additionalStatusInfo,
        );
    }
}
