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

    /** @param array<string, mixed> $statusReasonInfos */
    public static function fromArray(array $statusReasonInfos): self
    {
        /*
        * this can look like:
        *
        * <StsRsnInf>
        *   <AddtlInf>Status : Processing Failed</AddtlInf>
        * </StsRsnInf>
        *
        * or:
        *
        * <StsRsnInf>
        *   <Rsn>
        *     <Cd>NARR</Cd>
        *   </Rsn>
        *   <AddtlInf>Status : Failed - Invalid Account Number</AddtlInf>
        *   <AddtlInf>Error Code : !%</AddtlInf>
        *   <AddtlInf>Error Description : ACCOUNT NO INVALID</AddtlInf>
        * </StsRsnInf>
        *
        * To account for both, just use pluck and flatten
        */

        /** @var Collection<int, string> $infos */
        $infos = collect($statusReasonInfos)
            ->pluck('AddtlInf')
            ->flatten()
            ->values();

        return new self($infos);
    }
}
