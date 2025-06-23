<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Carbon\Carbon;

class GroupHeader
{
    public function __construct(
        public string $messageId,
        public Carbon $creditDateTime,
        public string $initiatingPartyName,
        public string $initiatingPartyBicOrBei,
    ) {}
}
