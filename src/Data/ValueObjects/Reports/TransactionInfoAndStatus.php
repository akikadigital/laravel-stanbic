<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Enums\TransactionStatusType;
use Illuminate\Support\Collection;
use Saloon\XmlWrangler\XmlReader;

class TransactionInfoAndStatus
{
    public function __construct(
        public string $originalInstrumentId,
        public string $originalEndToEndId,
        public TransactionStatusType $status,
        public StatusReasonInfos $statusReasonInfos,
    ) {}

    /** @return Collection<int, static> */
    public static function fromXmlReader(XmlReader $reader, string $root): Collection
    {
        $root = "{$root}/TxInfAndSts";

        $count = $reader->xpathValue($root)->collect()->count();

        /** @var Collection<int, static> */
        $transactionInfos = collect(range(0, $count - 1))
            ->map(function (int $key) use ($reader, $root) {
                // XML arrays start at 1
                $i = $key + 1;
                $path = "{$root}[{$i}]";

                /** @var string */
                $originalInstrumentId = $reader->xpathValue("{$path}/OrgnlInstrId")->sole();

                /** @var string */
                $originalEndToEndId = $reader->xpathValue("{$path}/OrgnlEndToEndId")->sole();

                /** @var string */
                $status = $reader->xpathValue("{$path}/TxSts")->sole();

                $additionalStatusInfos = StatusReasonInfos::fromXmlReader($reader, $path);

                return new self(
                    $originalInstrumentId,
                    $originalEndToEndId,
                    TransactionStatusType::from($status),
                    $additionalStatusInfos,
                );
            });

        return $transactionInfos;
    }
}
