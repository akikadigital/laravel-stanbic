<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Carbon\Carbon;
use Saloon\XmlWrangler\XmlReader;

class GroupHeader
{
    public function __construct(
        public string $messageId,
        public Carbon $creditDateTime,
        public string $initiatingPartyName,
        public string $initiatingPartyBicOrBei,
    ) {}

    public static function fromXmlReader(XmlReader $reader): self
    {
        /** @var string */
        $messageId = $reader->value('CstmrPmtStsRpt.GrpHdr.MsgId')->sole();

        /** @var string */
        $creditDateTime = $reader->value('CstmrPmtStsRpt.GrpHdr.CreDtTm')->sole();

        /** @var string */
        $initiatingPartyName = $reader->value('CstmrPmtStsRpt.GrpHdr.InitgPty.Nm')->sole();

        /** @var string */
        $initiatingPartyBicOrBei = $reader->value('CstmrPmtStsRpt.GrpHdr.InitgPty.Id.OrgId.BICOrBEI')->sole();

        return new GroupHeader(
            $messageId,
            Carbon::parse($creditDateTime),
            $initiatingPartyName,
            $initiatingPartyBicOrBei,
        );
    }
}
