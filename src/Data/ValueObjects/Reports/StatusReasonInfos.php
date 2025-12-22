<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Illuminate\Support\Collection;
use Saloon\XmlWrangler\XmlReader;

class StatusReasonInfos
{
    /** @param Collection<int, string> $additionalInfos */
    public function __construct(public Collection $additionalInfos) {}

    public static function fromXmlReader(XmlReader $reader, string $root): self
    {
        /** @var \Illuminate\Support\Collection<int, string> */
        $infos = $reader->xpathValue("{$root}/StsRsnInf/AddtlInf")->collect();

        return new self($infos);
    }
}
