<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Enums\GroupStatusType;
use Saloon\XmlWrangler\XmlReader;

/**
 * Customer payment status report
 */
class Pain00200103
{
    public function __construct(public XmlReader $xmlReader) {}

    public static function fromXml(string $xml): self
    {
        return new self(XmlReader::fromString($xml));
    }

    public function getGroupStatus(): GroupStatusType
    {
        /** @var string $value */
        $value = $this->xmlReader->value('CstmrPmtStsRpt.OrgnlGrpInfAndSts.GrpSts')->sole();
        $type = GroupStatusType::from($value);

        return $type;
    }
}
