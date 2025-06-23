<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Illuminate\Support\Collection;

class StatusReasonInfos
{
    /** @param Collection<int, string> $infos */
    public function __construct(public Collection $infos) {}
}
