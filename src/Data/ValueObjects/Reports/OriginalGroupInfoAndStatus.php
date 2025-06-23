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
        public ?string $additionalStatusInfo
    ) {}

    public static function fromXmlReader(XmlReader $reader): self
    {
        $root = 'CstmrPmtStsRpt.OrgnlGrpInfAndSts';

        /** @var string */
        $originalMessageId = $reader->value("{$root}.OrgnlMsgId")->sole();

        /** @var string */
        $originalMessageNameId = $reader->value("{$root}.OrgnlMsgNmId")->sole();

        /** @var string */
        $originalCreditDateTime = $reader->value("{$root}.OrgnlCreDtTm")->sole();

        /** @var string */
        $originalNumberOfTransactions = $reader->value("{$root}.OrgnlNbOfTxs")->sole();

        /** @var string */
        $originalControlSum = $reader->value("{$root}.OrgnlCtrlSum")->sole();

        /** @var string */
        $groupStatus = $reader->value("{$root}.GrpSts")->sole();

        /** @var string|null */
        $additionalStatusInfo = $reader->value("{$root}.StsRsnInf.AddtlInf")->first();

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
