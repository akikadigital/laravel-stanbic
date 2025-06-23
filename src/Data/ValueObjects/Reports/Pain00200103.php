<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Saloon\XmlWrangler\XmlReader;

/**
 * Customer payment status report
 */
class Pain00200103
{
    public ?XmlReader $xmlReader = null;

    public static function fromXml(string $xml): self
    {
        $report = new self;
        $report->xmlReader = XmlReader::fromString($xml);

        return $report;
    }
}
