<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Enums\GroupStatusType;
use Carbon\Carbon;

class OriginalGroupInfoAndStatus
{
    public function __construct(
        public string $originalMessageId,
        public string $originalMessageNameId,
        public Carbon $originalCreditorDateTime,
        public int $originalNumberOfTransactions,
        public float $originalControlSum,
        public GroupStatusType $groupStatus,
    ) {}

    /** @param array<string, string> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['OrgnlMsgId'],
            $data['OrgnlMsgNmId'],
            Carbon::parse($data['OrgnlCreDtTm']),
            (int) $data['OrgnlNbOfTxs'],
            (float) $data['OrgnlCtrlSum'],
            GroupStatusType::from($data['GrpSts']),
        );
    }
}
