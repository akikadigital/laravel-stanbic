<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Enums\TransactionStatusType;
use Saloon\XmlWrangler\XmlReader;

class TransactionInfoAndStatus
{
    public function __construct(
        public string $originalInstrumentId,
        public string $originalEndToEndId,
        public TransactionStatusType $status,
        public ?string $additionalStatusInfo,
    ) {}

    public static function fromXmlReader(XmlReader $reader): self
    {
        $root = 'CstmrPmtStsRpt.OrgnlPmtInfAndSts.TxInfAndSts';

        /** @var string */
        $originalInstrumentId = $reader->value("{$root}.OrgnlInstrId")->sole();

        /** @var string */
        $originalEndToEndId = $reader->value("{$root}.OrgnlEndToEndId")->sole();

        /** @var string */
        $status = $reader->value("{$root}.TxSts")->sole();

        /** @var ?string */
        $additionalStatusInfo = $reader->value("{$root}.StsRsnInf.AddtlInf")->first();

        return new self(
            $originalInstrumentId,
            $originalEndToEndId,
            TransactionStatusType::from($status),
            $additionalStatusInfo,
        );
    }
}
