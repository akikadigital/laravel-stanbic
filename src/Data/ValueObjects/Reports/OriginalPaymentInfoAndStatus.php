<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Illuminate\Support\Collection;
use Saloon\XmlWrangler\XmlReader;

class OriginalPaymentInfoAndStatus
{
    /** @param Collection<int, TransactionInfoAndStatus> $transactionInfoAndStatuses */
    public function __construct(
        public string $originalPaymentInfoId,
        public StatusReasonInfos $statusReasonInfos,
        public Collection $transactionInfoAndStatuses,
    ) {}

    /** @return Collection<int, static> */
    public static function fromXmlReader(XmlReader $reader): ?Collection
    {
        $root = '//CstmrPmtStsRpt/OrgnlPmtInfAndSts';

        $count = $reader->xpathValue($root)->collect()->count();

        if (! $count) {
            return null;
        }

        /** @var Collection<int, static> */
        $records = collect(range(0, $count - 1))
            ->map(function (int $key) use ($reader, $root) {
                $i = $key + 1;
                $path = "{$root}[{$i}]";

                /** @var string */
                $originalPaymentInfoId = $reader->xpathValue("{$path}/OrgnlPmtInfId")->sole();

                return new self(
                    $originalPaymentInfoId,
                    StatusReasonInfos::fromXmlReader($reader, $path),
                    TransactionInfoAndStatus::fromXmlReader($reader, $path),
                );
            });

        return $records;
    }
}
