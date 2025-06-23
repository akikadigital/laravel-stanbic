<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Saloon\XmlWrangler\XmlReader;

class OriginalPaymentInfoAndStatus
{
    public function __construct(
        public string $originalPaymentInfoId,
        public ?string $additionalStatusInfo,
        public TransactionInfoAndStatus $transactionInfoAndStatus,
    ) {}

    public static function fromXmlReader(XmlReader $reader): ?self
    {
        $root = 'CstmrPmtStsRpt.OrgnlPmtInfAndSts';

        if (! $reader->value($root)->first()) {
            return null;
        }

        /** @var string */
        $originalPaymentInfoId = $reader->value("{$root}.OrgnlPmtInfId")->sole();

        /** @var ?string */
        $additionalStatusInfo = $reader->value("{$root}.StsRsnInf.AddtlInf")->first();

        $transactionInfoAndStatus = TransactionInfoAndStatus::fromXmlReader($reader);

        return new self($originalPaymentInfoId, $additionalStatusInfo, $transactionInfoAndStatus);
    }
}
