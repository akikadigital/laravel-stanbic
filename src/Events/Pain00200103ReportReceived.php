<?php

namespace Akika\LaravelStanbic\Events;

use Akika\LaravelStanbic\Data\ValueObjects\Reports\Pain00200103;
use Illuminate\Foundation\Events\Dispatchable;

class Pain00200103ReportReceived
{
    use Dispatchable;

    public function __construct(public Pain00200103 $report) {}
}
