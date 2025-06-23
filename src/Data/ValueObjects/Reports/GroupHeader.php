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
        $root = '//CstmrPmtStsRpt/GrpHdr';

        /** @var string */
        $messageId = $reader->xpathValue("{$root}/MsgId")->sole();

        /** @var string */
        $creditDateTime = $reader->xpathValue("{$root}/CreDtTm")->sole();

        /** @var string */
        $initiatingPartyName = $reader->xpathValue("{$root}/InitgPty/Nm")->sole();

        /** @var string */
        $initiatingPartyBicOrBei = $reader->xpathValue("{$root}/InitgPty/Id/OrgId/BICOrBEI")->sole();

        return new GroupHeader(
            $messageId,
            Carbon::parse($creditDateTime),
            $initiatingPartyName,
            $initiatingPartyBicOrBei,
        );
    }
}
